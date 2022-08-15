<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NO_AGENT_CHECK", true);
define('BX_SESSION_ID_CHANGE', false);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;

$result = [
    'STATUS' => 'ERROR',
    'MESSAGE' => 'Не известная ошибка запроса'
];
$componentController = 'astash:user.controller';

try {
    [$version, $module, $method] = array_values(array_diff(explode('/', $_SERVER['REQUEST_URI']), ['', 'api']));

    if (!$method) {
        throw new \Exception('не передан метод модуля api');
    }

    if (!CBitrixComponent::includeComponentClass($componentController)) {
        throw new \Exception('компонент контроллер '.$componentController.' не найден');
    }

    $result = $APPLICATION->IncludeComponent($componentController, '', [
        'action' => $method
    ], false);

    $result = [
        'STATUS' => 'OK',
        'DATA' => $result
    ];
} catch (\Exception $e) {
    $result = [
        'STATUS' => 'ERROR',
        'MESSAGE' => $error = $e->getMessage()
    ];
}

$APPLICATION->RestartBuffer();
echo CUtil::PhpToJsObject($result);
die();