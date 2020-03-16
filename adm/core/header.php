<?php

class head {

    public $js = array(
        "jquery-1.7.2",
        "jquery.livequery",
        "jquery.undoable",
        "printer.js",
        "jQuery.print",
        "bootstrap.min",
        "jquery.qrcode.min",
        "jquery-ui",
        "jquery.url",
        "ajax"
    );
    public $css = array(
        "bootstrap.min",
        "bootstrap-theme.min",
        "jquery-ui",
        "main"
    );
    public $jscom = array();
    public $csscom = array();
    public $menu = array();
    public $context = "";
    public $hasident = false;

    public function menu() {
        if ($this->hasident) {
//Construction du menu depuis la base
            $model = new Model();
            $db = $model->getDb();

            //recupérer les paramétre utilisaeur et groupe d'access
            $auth = Zend_Auth::getInstance();
            $storage = $auth->getStorage();
            $identity = $auth->getIdentity();
            $user_groupe = $identity->id_acg;



            //Recupérer les groupe
            //$qr = $db->select()->from("s_modules")->where("s_modules.type = ?","g")->where("s_modules.mode = ?","ad");
            $qr = $db->select()->distinct()->from(array("p" => "s_modules"))->join(array('m' => 's_menu_affectation'), 'p.id = m.id_module_g', array())->join(array('k' => 's_access_menu_list'), 'k.id_module = m.id_module_g', array())->where("m.id_acg = ?", $user_groupe)->where("p.mode = ?", "ad")->where("k.id_acg = ?", $user_groupe);
            $res = $qr->query();
            $result = $res->fetchAll();
            //die(var_dump($result));

            //Recupérer les sous-groupes et crée l'arboréscence du menu
            foreach ($result as $groupes) {

                $qrmd = $db->select()->distinct()->from(array("p" =>"s_modules"))->join(array("l"=>"s_menu_affectation"),"p.id = l.id_module_m",array())->join(array('k' => 's_access_menu_list'), 'k.id_module = l.id_module_m', array())->where('l.id_module_g = ?', $groupes["id"])->where('l.id_acg = ?', $user_groupe)->where("p.mode = ?", "ad")->where("k.id_acg = ?", $user_groupe);
                $resmd = $qrmd->query();
                $resultmd = $resmd->fetchAll();
                
                $menu_array = array();
                $menu_array["item"] = $groupes["front_name"];
                if (!empty($groupes["path"])) {
                    $menu_array["link"] = "/" . $groupes ["path"];
                }
                if (!empty($resultmd)) {
                    $module_array = array();
                    foreach ($resultmd as $modules) {
                        array_push($module_array, array(
                            "items" => $modules["front_name"],
                            "links" => $modules["path"] . "/" . $modules["task"]
                        ));
                    }
                    $menu_array["modules"] = $module_array;
                };
                array_push($this->menu, $menu_array);
            }

            $menupath = ADROOT . "/tpl/menu.php";
            require ("$menupath");
        }
    }

    public function chemin() {
        /*
          $fullurl = str_replace("/SGS/ad/", "", $_SERVER["REDIRECT_URL"]);
          $lastshar = substr($fullurl, -1);
          if ($lastshar == "/") {
          $fullurl = substr_replace($fullurl, "", -1);
          }
          $param = explode("/", $fullurl);
          $url_array = array();
          $task_name_array = array();
          $namespace = new Zend_Session_Namespace();

          for ($i = 0; $i < count($param); $i++) {
          if ($i == 0) {
          $url = SCRIPTADROOT . $param[$i];
          } else {
          $url .= "/" . $param[$i];
          }

          $table = Model::getTable("modules");
          $qr_for_module = $table->select()->where("path = ?", $param[$i])->where("task = ?", "index");
          $res_for_module = $qr_for_module->query();
          $rest_for_module = $res_for_module->fetchAll();
          $task_rest = $rest_for_module[0]["front_name"];
          array_push($task_name_array, $task_rest);

          array_push($url_array, $url);
          }
          $menupath = ADROOT . "/tpl/chemin.php";
          require ("$menupath");
         * 
         */
    }

    public function getHead() {

        $libwebroot = SCRIPTROOT . "lib/";
        $libcomwebroot = SCRIPTADROOT . "tpl/" . $this->context . "/lib";

        $js = $this->js;
        $css = $this->css;
        $jscom = $this->jscom;
        $csscom = $this->csscom;

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
    }

    public function setContext($context) {
        $this->context = $context;
    }

    public function addJs($js) {
        if (gettype($js) == "array") {
            $this->js = array_merge($js, $this->jscom);
        } else if (gettype($js) == "string") {
            array_push($this->jscom, $js);
        } else {
            return false;
        }
    }

    public function addDefaultJs() {
        if (!empty($this->context)) {
            array_push($this->jscom, $this->context);
        }
    }

    public function addDefaultsCss() {
        if (!empty($this->context)) {
            array_push($this->csscom, $this->context);
        }
    }

    public function addCss($css) {
        if (gettype($css) == "array") {
            $this->csscom = array_merge($css, $this->csscom);
        } else if (gettype($css) == "string") {
            array_push($this->csscom, $css);
        } else {
            return false;
        }
    }

}

?>
