<?php

Class Exceptions {

    private static $_instance = null;
    private static $Exception = array();
    private static $namespace = null;

    private function __construct() {
        
    }

    public static function init() {
        self::$namespace = new Zend_Session_Namespace();
        if (empty(self::$namespace->LastExceptionString)) {
            self::$namespace->LastExceptionString = array();
        }
        self::$Exception = parse_ini_file(PATH_ROOT . DS . "core/application/exceptions.ini");
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Exceptions();
        }
        return self::$_instance;
    }

    public static function getException($code = null) {
        return self::$Exception;
    }

    public static function getExceptionByCode($code = null) {
        return self::$Exception[$code];
    }

    public static function getLastException() {
        return self::$namespace->LastExceptionString;
    }

    public static function setLastException($excString) {

        if (mb_detect_encoding($excString, 'UTF-8', true)) {
            array_push(self::$namespace->LastExceptionString, $excString);
        } else {
            array_push(self::$namespace->LastExceptionString, utf8_encode($excString));
        }
        
    }

    public static function clearException() {

        self::$namespace->LastExceptionString = null;
    }

}
