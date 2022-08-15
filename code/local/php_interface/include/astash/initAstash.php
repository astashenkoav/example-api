<?php
/**
 * Все доработки от astash
 * тут подключение всех файлов классов и так далее
 * файл не раздувать тут только подключение основных файлов
 *
 */

define("ASTASH_FULL_PATH", dirname(__FILE__));
$documentRoot = $_SERVER["DOCUMENT_ROOT"];
if (strpos($_SERVER["DOCUMENT_ROOT"], '/') !== false && DIRECTORY_SEPARATOR != '/') {
    $documentRoot = str_replace('/', '\\', $_SERVER["DOCUMENT_ROOT"]);
}
define("ASTASH_PATH", str_replace($documentRoot, '', ASTASH_FULL_PATH));

//Функции
if (file_exists(ASTASH_FULL_PATH."/functions.php")) {
    require_once(ASTASH_FULL_PATH."/functions.php");
}

// Подключение классов
if (file_exists(ASTASH_FULL_PATH.'/loaderAstash.php')) {
    require_once(ASTASH_FULL_PATH.'/loaderAstash.php');
}