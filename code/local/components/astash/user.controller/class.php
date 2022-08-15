<?php

use Astash\Orm\UserTokenTable;
use Bitrix\Main\Type;
use Astash\ActionFilter;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


class userController extends \Astash\RestComponents
{

    /**
     * Массив поддерживаемых actions
     *
     * @return array
     */
    protected function configureActions(): array
    {
        return [
            'auth' => [
                'prefilters' => [
                    new ActionFilter\RequestType(['GET', 'POST']),
                ],
            ],
            'createUser' => [
                'prefilters' => [
                    new ActionFilter\Authorization(),
                    new ActionFilter\RequestType(['GET', 'POST']),
                ],
            ],
            'getUserById' => [
                'prefilters' => [
                    new ActionFilter\Authorization(),
                    new ActionFilter\RequestType(['GET', 'POST']),
                ],
            ],
            'removeUserById' => [
                'prefilters' => [
                    new ActionFilter\Authorization(),
                    new ActionFilter\RequestType(['GET', 'POST']),
                ],
            ]
        ];
    }

    /**
     * Получение токена
     *
     * @throws Exception
     */
    protected function authAction($params)
    {
        $authParams = [
            "LOGIN" => $params['login'],
            "PASSWORD" => $params['pass'],
            "REMEMBER" => 'N',
            "PASSWORD_ORIGINAL" => 'Y',
        ];

        $result_message = true;
        $userId = CUser::LoginInternal($authParams, $result_message);
        if (!$userId) {
            throw new \Exception($result_message['MESSAGE']);
        }

        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $fields = [
            'USER_ID' => $userId,
            'TOKEN' => $token
        ];

        try {
            $res = UserTokenTable::getList(['filter' => ['USER_ID' => $userId]])->fetch();

            if ($res) {
                $fields['DATE_CREATE'] = new Type\DateTime();
                $resUpdate = UserTokenTable::update($res['ID'], $fields);
            } else {
                $resUpdate = UserTokenTable::add($fields);
            }

            if (!$resUpdate->isSuccess()) {
                throw new \Exception('ошибка получения токена');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->arResult = [
            'token' => $token
        ];
    }

    /**
     * Получить свойтва пользваотеля и его групп по USER_ID
     *
     * @param $params
     *
     * @throws \Exception
     */
    protected function getUserByIdAction($params)
    {
        if (!isset($params['USER_ID']) && !intval($params['USER_ID'])) {
            throw new \Exception('не передан "USER_ID"');
        }

        $user = \Bitrix\Main\UserTable::getList([
            'filter' => [
                'ID' => $params['USER_ID']
            ]
        ])->fetch();

        if (!$user) {
            throw new \Exception('пользователь не найден');
        }

        $groups = \Bitrix\Main\UserGroupTable::getList([
            'filter' => [
                'USER_ID' => $user['ID']
            ]
        ])->fetchAll();

        $groupsId = array_map(function ($item) {
            return $item["GROUP_ID"];
        }, $groups ?? []);

        $user['GROUPS'] = \Bitrix\Main\GroupTable::getList([
            'filter' => [
                'ID' => $groupsId
            ]
        ])->fetchAll();

        $this->arResult = [
            'user' => $user
        ];
    }

    /**
     * Создание пользователя по USER_ID и получения логина и пароля
     *
     * @param $params
     *
     * @return void
     * @throws Exception
     */
    protected function createUserAction($params)
    {
        if (!array_diff(['name', 'email', 'phone'], $params)) {
            throw new \Exception('не переданы обязательные параметры "name","email","phone"');
        }

        if (!strlen(trim($params['name']))) {
            throw new \Exception('поле "name" обязательное для заполнения');
        }

        if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('не формат поля "email"');
        }

        if (!preg_match('/^\d+$/', $params['phone'])) {
            throw new \Exception('не формат поля "phone"');
        }

        $user = \Bitrix\Main\UserTable::getList([
            'filter' => [
                'LOGIN' => $params['phone']
            ]
        ])->fetch();

        if ($user) {
            throw new \Exception('пользователь уже существует');
        }

        $pass = randString(8);
        $fields = [
            'LOGIN' => $params['phone'],
            'PASSWORD' => $pass,
            'NAME' => $params['name'],
            'LAST_NAME' => $params['lastName'] ?? '',
            'EMAIL' => $params['email'],
            'PERSONAL_PHONE' => $params['phone'],
        ];

        $newUserId = (new CUser())->Add($fields);
        $this->arResult = [
            'user_id' => $newUserId,
            'password' => $pass,
            'login' => $params['phone']
        ];
    }

    /**
     * Удаления пользователя по USER_ID
     *
     * @throws \Exception
     */
    protected function removeUserByIdAction($params)
    {
        $user = \Bitrix\Main\UserTable::getList([
            'filter' => [
                'ID' => $params['USER_ID']
            ]
        ])->fetch();

        if (!$user) {
            throw new \Exception('пользователь не найден');
        }

        $res = CUser::Delete($params['USER_ID']);
        if (!$res) {
            throw new \Exception('ошибка удаления пользователя');
        }

        $res = UserTokenTable::getList(['filter' => ['USER_ID' => $params['USER_ID']]])->fetch();
        if ($res) {
            UserTokenTable::delete($res['ID']);
        }

        $this->arResult = [
            'delete' => true
        ];
    }
}