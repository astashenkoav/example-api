<?php
/**
 * Подключение классов
 */

use Bitrix\Main\Loader;

try {
    $arClasses = [
        'Astash\HighLoadBlockHelper' => ASTASH_PATH.'/classes/HighLoadBlockHelper.php',

        'Astash\RestComponents' => ASTASH_PATH.'/classes/RestComponents.php',
        'Astash\Orm\UserTokenTable' => ASTASH_PATH.'/classes/orm/UserTokenTable.php',
        'Astash\ActionFilter\Base' => ASTASH_PATH.'/classes/action_filter/Base.php',
        'Astash\ActionFilter\Authorization' => ASTASH_PATH.'/classes/action_filter/Authorization.php',
        'Astash\ActionFilter\RequestType' => ASTASH_PATH.'/classes/action_filter/RequestType.php',
    ];

    Loader::registerAutoLoadClasses(null, $arClasses);
} catch (\Bitrix\Main\LoaderException $e) {
}