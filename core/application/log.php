<?php

class log {

    private static $_instance = null;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new view();
        }
        return self::$_instance;
    }

    public static function logtxt($string) {
        $handle = fopen(PATH_ROOT . DS . "core/application/log.txt", "a+");
        fwrite($handle, $string . " \n");
        fclose($handle);
    }

}
