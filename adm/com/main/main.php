<?php

class main extends controler {

    public function index() {

        $this->redirectphp(SCRIPTROOT . "/gestion");

        $model = new model();

        view::setContext(get_class($this));

        view::addCss("stl1");

        view::addJs("cheklist");

        view::render("tpl");
    }
    
      public function asyn_isadmin() {
        if (Zend_Auth::getInstance()->getIdentity()->IDUTIL == 1) {
            echo json_encode(array("etat" => "1"));
        } else {
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_ajout_module() {

        $params = $this->get_passed_vars("data");
        $modnom = $params["nommodule"];

        $frontcompath = PATH_ROOT . DS . "com";
        $fronttplpath = PATH_ROOT . DS . "tpl";

        $pathcommod = $frontcompath . DS . $modnom;
        $pathtplmod = $fronttplpath . DS . $modnom;


        $controller_content = "<?php 
            
                                        class " . $modnom . " extends controler {

                                                  public function index() {

                                                      view::setContext(get_class(" . "$" . "this));

                                                      view::addDefaultsCss();

                                                      view::addDefaultJs();

                                                      view::render('tpl');

                                                  } 
                                        }

                                ?>";

        $tpl_content = "<div class='well col-md-6  col-md-offset-3'> bienvenu </div>";

        try {

            mkdir($pathcommod, 0777);
           

            $fp = fopen($pathcommod . DS . $modnom . '.php', 'w');
            fwrite($fp, $controller_content);
            fclose($fp);

            mkdir($pathtplmod, 0777);
           

            $fp = fopen($pathtplmod . DS . 'tpl.php', 'w');
            fwrite($fp, $tpl_content);
            fclose($fp);

            mkdir($pathtplmod . DS . "lib", 0777);

            mkdir($pathtplmod . DS . "lib" . DS . "css", 0777);
            $fp = fopen($pathtplmod . DS . "lib" . DS . "css" . DS . $modnom . ".css", 'w');
            fwrite($fp, "");
            fclose($fp);

            mkdir($pathtplmod . DS . "lib" . DS . "img", 0777);

            mkdir($pathtplmod . DS . "lib" . DS . "js", 0777);
            $fp = fopen($pathtplmod . DS . "lib" . DS . "js" . DS . $modnom . ".js", 'w');
            fwrite($fp, "");
            fclose($fp);

            Message::set_info_msg("Module " . $modnom . " crée !");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_prim_function() {

        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        $frontcompath = PATH_ROOT . DS . "com";

        $rqpt = $db->select()->distinct()->from("modul", "MODULpath")->where("MODULmod = ?", "front");
        $rspt = $rqpt->query();
        $path = $rspt->fetchAll();

        try {

            foreach ($path as $item) {

                $rqtsk = $tables->select()->where("MODULmod = ?", "front")->where("MODULpath = ?", $item["MODULpath"]);
                $rstsk = $rqtsk->query();
                $path = $rstsk->fetchAll();
            }
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_deconection() {
        Zend_Auth::getInstance()->clearIdentity();
        echo json_encode(array("etat" => "1"));
    }

    public function asyn_create_exception() {
        Exceptions::setLastException(Exceptions::getExceptionByCode(1004));
        Message::set_info_msg("bienvenu");
        $arr = array(
            "etat" => 3,
        );
        echo json_encode($arr);
    }

    public function asyn_get_msg() {
        $msg = Message::get_info_msg();
        Message::clear_info_msg();
        echo json_encode($msg);
    }

    public function asyn_get_exception() {
        $exceptions = Exceptions::getLastException();
        Exceptions::clearException();
        echo json_encode($exceptions);
    }

    public function asyn_get_all_right() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $idprf = Zend_Auth::getInstance()->getIdentity()->idPRF;
            $db = Model::getDb();

            $qrmd = $db->select()->from("droit", "DROITmark")->where("idDROIT IN ?", $db->select()->from("prf_has_droit", "idDROIT")->where("idPRF = ?", $idprf));
            $rsmd = $qrmd->query();
            $md = $rsmd->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $md));
        } else {
            echo json_encode(array("etat" => "0"));
        }
    }

}

?>