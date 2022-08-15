<?php
/**
 * Created by PhpStorm.
 * User: Alexander Astashchenko
 * Date: 14.08.2022
 * Time: 14:59
 */

namespace Astash\ActionFilter;

use Bitrix\Main\Context;
use Bitrix\Main\Error;

class RequestType extends Base
{
    private $types;

    public function __construct($types = [])
    {
        $this->types = $types ?? [];
        parent::__construct();
        $this->checkRequestType();
    }

    protected function checkRequestType()
    {
        $request = Context::getCurrent()->getRequest();
        if (!in_array($request->getRequestMethod(), $this->types)) {
            $this->addError(new Error('не поддерживаемый тип запроса'));
        }
    }
}