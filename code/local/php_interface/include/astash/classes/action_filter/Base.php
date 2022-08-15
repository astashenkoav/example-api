<?php
/**
 * Created by PhpStorm.
 * User: Alexander Astashchenko
 * Date: 14.08.2022
 * Time: 12:14
 */

namespace Astash\ActionFilter;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;

class Base
{
    /** @var  ErrorCollection */
    protected $errorCollection;

    final public static function className()
    {
        return get_called_class();
    }

    /**
     * Constructor Controller.
     */
    public function __construct()
    {
        $this->errorCollection = new ErrorCollection;
    }

    /**
     * Adds error to error collection.
     *
     * @param Error $error Error.
     *
     * @return $this
     */
    protected function addError(Error $error)
    {
        $this->errorCollection[] = $error;

        return $this;
    }

    /**
     * Getting array of errors.
     * @return Error[]
     */
    final public function getErrors()
    {
        return $this->errorCollection->toArray();
    }

    /**
     * Getting once error with the necessary code.
     *
     * @param string $code Code of error.
     *
     * @return Error
     */
    final public function getErrorByCode($code)
    {
        return $this->errorCollection->getErrorByCode($code);
    }
}