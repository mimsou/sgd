<?php

class Message {

    public static $namespace = null;
    public static $css_class = "";
    public static $HtmlTag = "li";
    private static $_instance = null;

    function __construct() {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Message();
        }
        return self::$_instance;
    }

    public static function init() {

        self::$namespace = new Zend_Session_Namespace();

        if (empty(self::$namespace->errmsg)) {

            self::$namespace->errmsg = array();
        }
        if (empty(self::$namespace->infomsg)) {
            self::$namespace->infomsg = array();
        }
    }

    public static function set_error_msg($msg) {
        if (mb_detect_encoding($msg, 'UTF-8', true)) {
            array_push(self::$namespace->errmsg, $msg);
        } else {
            array_push(self::$namespace->errmsg, utf8_encode($msg));
        }
    }

    public static function set_info_msg($msg) {
        if (mb_detect_encoding($msg, 'UTF-8', true)) {
            array_push(self::$namespace->infomsg, $msg);
        } else {
            array_push(self::$namespace->infomsg, utf8_encode($msg));
        }
    }

    public static function SetHtmlTagList() {
        self::$HtmlTag = "li";
    }

    public static function SetHtmlTagDiv() {
        self::$HtmlTag = "div";
    }

    public static function has_msg() {
        if (empty(self::$namespace->errmsg) && empty(self::$namespace->infomsg)) {
            return false;
        }
        return true;
    }

    public static function has_err_msg() {
        if (empty(self::$namespace->errmsg)) {
            return false;
        }
        return true;
    }

    public static function has_info_msg() {
        if (empty(self::$namespace->infomsg)) {
            return false;
        }
        return true;
    }

    private static function _get_msg_html($msgTable) {
        $html = "";
        if (self::has_msg()) {

            if (self::$HtmlTag == "li") {

                $html .="<ul class='" . self::$css_class . "'>";
                for ($i = 0; $i < count($msgTable); $i++) {
                    $html .="<li>" . $msgTable[$i] . "</li>";
                }
                $html .="</ul>";
            } else if (self::$HtmlTag == "div") {

                $html .="<div class='" . self::$css_class . "'>";
                for ($i = 0; $i < count($msgTable); $i++) {
                    $html .="<div>" . $msgTable[$i] . "</div>";
                }
                $html .="</div>";
            }
            return $html;
        }
        return "";
    }

    public static function render_info_msg() {
        $html = self::_get_msg_html(self::$namespace->infomsg);
        self::$namespace->infomsg = null;
        return $html;
    }

    public static function get_info_msg() {
        return self::$namespace->infomsg;
    }

    public static function get_err_msg() {
        return self::$namespace->errmsg;
    }

    public static function clear_err_msg() {
        self::$namespace->errmsg = null;
    }

    public static function clear_info_msg() {
        self::$namespace->infomsg = null;
    }

    public static function clear_msg() {
        self::$namespace->infomsg = null;
        self::$namespace->errmsg = null;
    }

    public static function render_err_msg() {
        $html = self::_get_msg_html(self::$namespace->errmsg);
        self::$namespace->errmsg = null;
        return $html;
    }

    public static function render_msg() {
        $html = self::render_info_msg() . self::render_err_msg();
        self::$namespace->errmsg = null;
        self::$namespace->infomsg = null;
        return $html;
    }

}
