<?php

class View {

    private static $_instance = null;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new view();
        }
        return self::$_instance;
    }

    public static function init() {
        Message::init();
    }

    public static $jscom = array();
    public static $csscom = array();
    public static $menu = array();
    public static $data = array();
    public static $context = "";
    public static $hasident = false;
    public static $tpl = "";
    public static $tplhead = "";
    public static $js = array(
        "jquery-1.11.0.min",
        "vendors/bootstrap/dist/js/bootstrap.min",
        "jquery.metadata",
        "jquery.validate.min",
        "messages_fr",
        "additional-methods.min",
        "jquery.livequery",
        "jquery.undoable",
        "printer",
        "jQuery.print",
        "jstree",
        "printThis",
        "jquery.qrcode.min",
        "jquery.url",
        "jquery-ui",
        "select2.min",
        "jquery-ui-timepicker-addon",
        "jquery.tablesorter.min",
        "jquery.tablesorter.widgets",
        "jquery.tablesorter.pager",
        "jquery.floatThead",
        "jquery.mask.min",
        "jquery.tipsy",
        "extends",
        "jquery.contextMenu",
        "ajax",
        "numeral.min",
        "highcharts",
        "scalable-yaxis",
        "exporting",
        "jquery.jstree.grid",
        "vendors/fastclick/lib/fastclick",
        "vendors/nprogress/nprogress",
        "vendors/echarts/dist/echarts.min",
        "vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min",
        "vendors/jquery.hotkeys/jquery.hotkeys",
        "vendors/google-code-prettify/src/prettify",
        "vendors/datatables.net/js/jquery.dataTables.min",
        "custom",
        "JsBarcode.all"
        
    );
    public static $css = array(
        "vendors/bootstrap/dist/css/bootstrap.min",
        "bootstrap-theme.min",
        "vendors/font-awesome/css/font-awesome.min",
        "vendors/google-code-prettify/bin/prettify.min",
        "jquery-ui",
        "jstree",
        "tipsy",
        "select2",
        "select2-bootstrap.min",
        "jquery.contextMenu",
        "main",
        "custom"
    );

    public static function menu() {

        $html = '';

        if (Zend_Auth::getInstance()->hasIdentity() && MODE == "front") {

            $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;
            $db = Model::getDb();


            //menu module
            $qrmd = $db->select()->from("modul")->where("idMODUL IN ?", $db->select()->from("droit_has_modul", "idMODUL")->where("idDROIT IN ?", $db->select()->from("prf_has_droit", "idDROIT")->where("idPRF = ?", $idprf)))->order("MODULmnseq ASC");
            ;
            $rsmd = $qrmd->query();
            $md = $rsmd->fetchAll();

            //menu
            $allmmd = $db->select()->from("modul", "idMENU")->where("idMODUL IN ?", $db->select()->from("droit_has_modul", "idMODUL")->where("idDROIT IN ?", $db->select()->from("prf_has_droit", "idDROIT")->where("idPRF = ?", $idprf)));
            $qrmn = $db->select()->from("menu")->where("idMENU IN ?", $allmmd)->where("idMENU != ?", 0)->where("idMENU != ?", 0)->order("MENUmnseq ASC");
            $rsmn = $qrmn->query();
            $upmenu = $rsmn->fetchAll();

            $qrmna = $db->select()->from("menu")->where("idMENU != ?", 0)->order("MENUmnseq ASC");
            $rsmna = $qrmna->query();
            $upmenua = $rsmna->fetchAll();



            foreach ($upmenua as $itema) {
                foreach ($upmenu as $item) {
                    if (($item["MENUparent"] == $itema["idMENU"]) && !in_array($itema, $upmenu)) {
                        array_push($upmenu, $itema);
                    }
                }
            }

            usort($upmenu, function($a, $b) {
                return strcmp($a["MENUmnseq"], $b["MENUmnseq"]);
            });

            $menu = array();
            //echo (var_dump($upmenu));
            foreach ($upmenu as $item) {
                if ($item["MENUparent"] == 0) {
                    $menuitem = array(
                        "id" => $item["idMENU"],
                        "nom" => $item["MENUnom"],
                        "type" => "mn",
                        "icon" => $item["MENUicon"],
                        "children" => self::_populate_menu_item($item["idMENU"], $upmenu, $md)
                    );

                    array_push($menu, $menuitem);
                }
            }

            //render html

            $html = '<ul class="nav side-menu">';
            foreach ($menu as $item) {
                $html .= '<li>';
                $html .= '<a><i class = "' . $item["icon"] . '"></i><span ></span> ';
                $html .= $item["nom"];
                $html .= '<span class="caret"></span></a>';
                if (!empty($item["children"])) {
                    $html .= '<ul class="nav child_menu">';
                    $html .= self::_render_submenu($item["children"]);
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        if (Zend_Auth::getInstance()->hasIdentity()) {
            require(BASE_PATH . DS . "tpl" . DS . "menu.php");
        }
    }

    private static function _populate_menu_item($id, $mnlist, $mdlist) {

        $submenu = array();

        foreach ($mnlist as $item) {
            if ($item["MENUparent"] == $id) {
                $submenuitem = array(
                    "id" => $item["idMENU"],
                    "nom" => $item["MENUnom"],
                    "type" => "mn",
                    "icon" => $item["MENUicon"],
                    "children" => self::_populate_menu_item($item["idMENU"], $mnlist, $mdlist)
                );
                array_push($submenu, $submenuitem);
            }
        }

        foreach ($mdlist as $item) {

            if ($item["idMENU"] == $id) {
                $submenuitemmd = array(
                    "id" => $item["idMODUL"],
                    "nom" => $item["MODULnom"],
                    "type" => "md",
                    "icon" => $item["MODULicon"],
                    "link" => SCRIPTROOT . "/" . $item["MODULpath"] . "/" . $item["MODULtask"],
                );
                array_push($submenu, $submenuitemmd);
            }
        }

        return $submenu;
    }

    public static function _render_submenu($itemmenus) {

        $htmlsub = "";
        foreach ($itemmenus as $itemmenu) {

            if ($itemmenu["type"] == "md") {
                $htmlsub .= '<li>';
                $htmlsub .= '<a href="' . $itemmenu["link"] . '"><span class = "' . $itemmenu["icon"] . '"></span>' . $itemmenu["nom"] . '</a>';
                $htmlsub .= '</li>';
            }

            if ($itemmenu["type"] == "mn") {
                $htmlsub .= '<li>';
                $htmlsub .= '<a href="javascript:void(0)" ><span class = "' . $itemmenu["icon"] . '"></span> ' . $itemmenu["nom"] . '</a>';
                if (!empty($itemmenu["children"])) {
                    $aritem = $itemmenu["children"];
                    $htmlsub .= '<ul class="nav child_menu">';
                    $htmlsub .= self::_render_submenu($aritem);
                    $htmlsub .= '</ul>';
                }
                $htmlsub .= '</li>';
            }
        }
        return $htmlsub;
    }

    public static function chemin() {

        if (MODE != "front" || !controler::connected())
            return false;

        $module = controler::getModul();

        $idm = $module["idMODUL"];

        $chemin = array();

        array_push($chemin, $module["MODULnom"]);

        $db = Model::getDb();

        $menu = $db->select()->from(array("A" => "menu"))->where("A.idMENU =  ?", $module["idMENU"])->query()->fetch();

        $html = "";

        if (!empty($menu)) {

            array_push($chemin, $menu["MENUnom"]);

            $a = true;

            while ($a) {
                if ($menu["MENUparent"] == 0) {
                    $a = false;
                } else {
                    $menu = $db->select()->from(array("A" => "menu"))->where("A.idMENU =  ?", $menu["MENUparent"])->query()->fetch();
                    array_push($chemin, $menu["MENUnom"]);
                }
            }
        } else {
            array_push($chemin, "Accueil");
        }

        $chemin = array_reverse($chemin);

        $html = "";

        foreach ($chemin as $item) {
            $html .= "<li>$item</li>";
        }

        echo $html;
    }

    public static function rendermodhead() {

        if (MODE != "front" || !controler::connected())
            return false;

        $module = controler::getModul();

        $path = $module["MODULpath"];

        $data = self::$data;
        if (file_exists(self::$tplhead)) {
            require(self::$tplhead);
        }
    }

    public static function getHead() {

        $var = new vars();
        if (MODE == "ad") {
            $base = str_replace("/" . $var->ad_dir, "", SCRIPTROOT);
        } else if (MODE == "front") {
            $base = SCRIPTROOT;
        }

        $libwebroot = $base . "/" . "lib/";
        $libcomwebroot = SCRIPTROOT . "/" . "tpl/" . self::$context . "/lib";


        $js = self::$js;
        $css = self::$css;
        $jscom = self::$jscom;
        $csscom = self::$csscom;


        echo '<script type="text/javascript">
              var path_base = "' . SCRIPTROOT . '";
             </script>';

        echo '<link rel="stylesheet" href="' . $libwebroot . 'css/bootstrap.min.css" type="text/css"  media="print" />';

        foreach ($js as $file) {
            echo '<script type="text/javascript" src="' . $libwebroot . "js/" . $file . '.js" ></script>';
        }

        foreach ($jscom as $file) {
            echo '<script type="text/javascript" src="' . $libcomwebroot . "/js/" . $file . '.js" ></script>';
        }

        foreach ($css as $file) {
            echo '<link rel="stylesheet" href="' . $libwebroot . "css/" . $file . '.css" type="text/css" />';
        }
        foreach ($csscom as $file) {
            echo '<link rel="stylesheet" href="' . $libcomwebroot . "/css/" . $file . '.css" type="text/css" />';
        }

//compatiblité bootstrap ie1-8
        if (preg_match('/(?i)msie [1-9]/', $_SERVER['HTTP_USER_AGENT'])) {
            echo '<link rel="stylesheet" href="' . $libwebroot . "css/" . 'ie-compatible.css" type="text/css" />';
            echo '<script type="text/javascript" src="' . $libwebroot . '/js/respond.min.js" ></script>';
            echo '<script type="text/javascript" src="' . $libwebroot . '/js/html5shiv.js" ></script>';
        }
    }

    public static function render($filename) {

        if (Zend_Auth::getInstance()->hasIdentity())
            self::$hasident = true;
        $data = self::$data;
        $tplroot = BASE_PATH . DS . "tpl/" . self::$context . "/";
        self::$tpl = BASE_PATH . DS . "tpl/" . self::$context . "/" . $filename . ".php";
        self::$tplhead = BASE_PATH . DS . "tpl/" . self::$context . "/" . $filename . "_head.php";
        self::getMsgFromException();
        if (CTL !== "login") {
            require(BASE_PATH . DS . "tpl/index.php");
        } else {
            require(BASE_PATH . DS . "tpl/login.php");
        }
    }

    public static function getMsgFromException() {
        $exception = Exceptions::getLastException();
        foreach ($exception as $item) {

            Message::set_error_msg($item);
        }
        Exceptions::clearException();
    }

    public static function newdata($data, $ref) {
        self::$data[$ref] = $data;
    }

    public static function message() {
        return Message::render_msg();
    }

    public static function util_bar() {
        if (Zend_Auth::getInstance()->hasIdentity() && (MODE == "front")) {
            require(BASE_PATH . DS . "tpl/utilbar.php");
        }
    }

    public static function renderCom() {
        $data = self::$data;
        if (file_exists(self::$tpl)) {
            require(self::$tpl);
        } else {
            //Exceptions::setLastException(array("Composant corrompu merci de contacter un administrateur","Erreur system"));
            controler::redirectphp(WEBROOT . "main");
        }
    }

    public static function setContext($context) {
        self::$context = $context;
    }

    public static function addJs($js) {
        if (gettype($js) == "array") {
            self::$js = array_merge($js, self::$jscom);
        } else if (gettype($js) == "string") {
            array_push(self::$jscom, $js);
        } else {
            return false;
        }
    }

    public static function addDefaultJs() {
        if (!empty(self::$context)) {
            array_push(self::$jscom, self::$context);
        }
    }

    public static function addDefaultsCss() {
        if (!empty(self::$context)) {
            array_push(self::$csscom, self::$context);
        }
    }

    public static function addCss($css) {
        if (gettype($css) == "array") {
            self::$csscom = array_merge($css, self::$csscom);
        } else if (gettype($css) == "string") {
            array_push(self::$csscom, $css);
        } else {
            return false;
        }
    }

}

?>
