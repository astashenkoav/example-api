<?php
/**
 * Created by PhpStorm.
 * User: Alexander Astashchenko
 * Date: 14.08.2022
 * Time: 10:36
 */

namespace Astash;

use Astash\ActionFilter;

abstract class RestComponents extends \CBitrixComponent
{

    /**
     *
     * @var
     */
    private $actions;

    /**
     * Массив actions [name_action=>[params..]]
     *
     * @return array
     */
    abstract protected function configureActions(): array;

    /**
     * @return void
     * @throws \Exception
     */
    private function checkActions()
    {
        $this->arParams['action'] = trim($this->arParams['action']);

        if (!strlen($this->arParams['action'])) {
            throw new \Exception('не передан action');
        }

        if (!count($this->actions)) {
            throw new \Exception('не определен массив actions');
        }

        if (!in_array($this->arParams['action'], array_keys($this->actions))) {
            throw new \Exception('не поддерживаемый action');
        }

        // Фильтры
        if ($this->actions[$this->arParams['action']]['prefilters']) {
            foreach ($this->actions[$this->arParams['action']]['prefilters'] as $filter) {

                if (!($filter instanceof ActionFilter\Base)) {
                    throw new \Exception('Filter has to be subclass of '.ActionFilter\Base::className());
                }


                if ($filter->getErrors()) {
                    throw new \Exception(implode(', ', $filter->getErrors()));
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function executeComponent()
    {
        $this->actions = $this->configureActions();
        $this->checkActions();

        if (method_exists($this, $this->arParams['action'].'Action')) {
            $this->{$this->arParams['action'].'Action'}($this->request->getValues());
        } else {
            throw new \Exception('не реализованный action: '.$this->arParams['action'].'Action');
        }

        return $this->arResult;
    }
}