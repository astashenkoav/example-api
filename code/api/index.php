<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NO_AGENT_CHECK", true);
define('BX_SESSION_ID_CHANGE', false);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;

$error = 'Не известная ошибка запроса.';
try {
    [$version, $module, $method] = array_values(array_diff(explode('/', $_SERVER['REQUEST_URI']), ['', 'api']));
    if (!$version) {
        throw new \Exception('не передана версия api');
    }

    if (!file_exists(__DIR__.'/'.$version)) {
        throw new \Exception('не поддерживаемая версия api');
    }

    if (!$module) {
        throw new \Exception('не передан модуль api');
    }

    if (!file_exists(__DIR__.'/'.$version.'/'.$module)) {
        throw new \Exception('неизвестный модуль api');
    }

    if (!$method) {
        throw new \Exception('не передан метод модуля api');
    }

} catch (\Exception $e) {
    $error = $e->getMessage();
}

$APPLICATION->RestartBuffer();
echo CUtil::PhpToJsObject([
    'STATUS' => 'ERROR',
    'ERROR' => $error
]);
die();