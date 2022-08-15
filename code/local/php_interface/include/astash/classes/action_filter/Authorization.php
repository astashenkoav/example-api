<?php
/**
 * Created by PhpStorm.
 * User: Alexander Astashchenko
 * Date: 14.08.2022
 * Time: 12:40
 */

namespace Astash\ActionFilter;

use Astash\Orm\UserTokenTable;
use Bitrix\Main\Error;
use Bitrix\Main\Type\DateTime;

class Authorization extends Base
{
    const TOKEN_TIME_ACTIVE = 3600;

    public function __construct()
    {
        parent::__construct();
        $this->checkToken();
    }

    protected function checkToken()
    {
        $token = '';
        $headers = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            $this->addError(new Error('токен авторизации отсутствует'));
        }

        $res = UserTokenTable::getList([
            'filter' => [
                'TOKEN' => $token
            ]
        ])->fetch();

        if (!$res) {
            $this->addError(new Error('токен не найден'));
        }

        try {
            $currentDateTime = new DateTime();
            $tokenDateTime = new DateTime($res["DATE_CREATE"]);
        } catch (\Exception $e) {
            $this->addError(new Error('неизвестная ошибка проверки токен'));
        }

        if ($currentDateTime->getTimestamp() - $tokenDateTime->getTimestamp() > self::TOKEN_TIME_ACTIVE) {
            $this->addError(new Error('токен протух'));
        }
    }
}