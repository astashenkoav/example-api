<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = array(
    "NAME" => GetMessage("ASTASH_USER_CONTROLLER_NAME"),
    "DESCRIPTION" => GetMessage("ASTASH_USER_CONTROLLER_DESCR"),
    "ICON" => "/images/system.empty.png",
    "CACHE_PATH" => "Y",
    "SORT" => 65,
    "COMPLEX" => "N",
    "PATH" => array(
        "ID" => "Astash",
        "NAME" => GetMessage("ASTASH_GROUP_NAME"),
    ),
);