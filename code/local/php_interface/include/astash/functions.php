<?php

if (!function_exists("dump")) {
    /**
     * @param $ar
     * @param $die
     * @param $all
     *
     * @return void
     */
    function dump($ar = '', $die = false, $all = false)
    {
        global $USER;
        if (!$USER) {
            $USER = new CUser();
        }
        if ($USER->IsAdmin() || $all) {
            echo '<pre>';
            var_dump($ar);
            echo '</pre>';
        }
        if ($die) {
            die();
        }
    }
}