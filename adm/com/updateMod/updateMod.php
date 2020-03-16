<?php

class updateMod extends controler {

    public function index() {
        
    }

    public function asyn_update_ad() {
        $params = $this->get_passed_vars("data");
        $this->_update("ad", $params["arb"]);
    }

    public function asyn_update_front() {
        $params = $this->get_passed_vars("data");
        $this->_update("front", $params["arb"]);
    }

    public function asyn_reset_update_state() {
        Model::getDb();
        $tables = new Zend_Db_Table('modul');
        $data = array(
            'MODULupdate' => 0,
        );

        $tables->update($data, "");
    }

    public function asyn_get_com_arborecense() {

        $params = $this->get_passed_vars("data");
        if (!isset($params["mode"]))
            return false;

        if ($params["mode"] == "ad")
            $root = BASE_PATH . DS . "com";
        else if ($params["mode"] == "front")
            $root = PATH_ROOT . DS . "com";
        else
            exit;

        $allcomlist = array();
        if ($handle = opendir($root)) {
            $blacklist = array('.', '..');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist)) {
                    if (strpos($file, ".") === false) {
                        array_push($allcomlist, $file);
                    }
                }
            }
            closedir($handle);
        }
        echo json_encode($allcomlist);
    }

    private function _isinstring($string, $search) {
        if (strpos($search, $string) !== false) {
            return true;
        }
        return false;
    }

    private function _update($mode, $comp) {

        $erreur = 0;
        $modul = 0;
        $msgok ="";

        //recupérer les dossier des composant
        if ($mode == "ad")
            $root = BASE_PATH . DS . "com";
        else if ($mode == "front")
            $root = PATH_ROOT . DS . "com";



        if (file_exists($root . DS . $comp . DS . $comp . ".php")) {

            if (class_exists($comp, false) == false) {

                require ($root . DS . $comp . DS . $comp . ".php");
            }

            if (class_exists($comp)) {

                $methode_parent = get_class_methods("controler");
                $methode_with_extended = get_class_methods($comp);
                $action = array();

                foreach ($methode_with_extended as $search) {

                    if (strpos($search, "asyn_") !== false) {
                        $discript = "la fonction asynchrone ";
                    }else{
                        $discript = "l'interface ";
                    }

                    $reflection = new ReflectionMethod($comp, $search);

                    if ($reflection->isPublic()) {

                        if (!in_array($search, $methode_parent)) {


                            Model::getDb();
                            $tables = new Zend_Db_Table('modul');

                            $qr = $tables->select()->where("MODULpath = ?", $comp)->where("MODULtask = ?", $search)->where("MODULmod = ?", $mode);
                            $res = $qr->query();
                            $rest = $res->fetchAll();

                            if ($this->_isinstring("asyn_", $search)) {
                                $type = "asyn";
                            } else if ($this->_isinstring("sync_", $search)) {
                                $type = "sync";
                            } else {
                                $type = "vu";
                            }

                            if (empty($rest)) {

                                $data = array(
                                    'MODULpath' => $comp,
                                    'MODULtask' => $search,
                                    'MODULnom' => $comp . "-" . $search,
                                    'idETAT' => 21,
                                    'idMENU' => 0,
                                    'MODULupdate' => 1,
                                    'MODULmod' => $mode,
                                    'MODULdcr' => new Zend_Db_Expr('NOW()'),
                                    'MODULtype' => $type
                                );

                                if (!$tables->insert($data)) {
                                    $erreur ++;
                                    $msgok .= "<div> Erreur de mise à jours > $discript :" . $search . "dans le composant :" . $comp."</div>";
                                } else {

                                    $erreur ++;
                                    $modul++;
                                    $msgok .= "<div style='color:red'> $discript :" . $search . " dans le composant :" . $comp . " est ajouté avec succés </div>";
                                }
                            } else {

                                $data = array(
                                    'MODULupdate' => 1,
                                );



                                $tables->update($data, "MODULpath = '" . $comp . "' and  MODULtask = '" . $search . "' and MODULmod = '" . $mode . "'");
                                $msgok .= "<div> $discript :" . $search . " dans le composant :" . $comp . " est mis à jours </div>";
                            }
                        }
                    }
                }
            }
        }

        if ($erreur != 0) {
            echo json_encode(array("etat" => "0", "msg" => $msgok,"comp"=>$comp,"mode"=>$mode));
        } else if ($modul != 0) {
            echo json_encode(array("etat" => "0", "msg" => $msgok,"comp"=>$comp,"mode"=>$mode));
        } else {
            echo json_encode(array("etat" => "0", "msg" => $msgok,"comp"=>$comp,"mode"=>$mode));
        }
    }

    public function asyn_get_net_modul() {

        Model::getDb();
        $tables = new Zend_Db_Table('modul');
        $qr = $tables->select()->where("MODULupdate = ?", 0);
        $res = $qr->query();
        $rest = $res->fetchAll();

        echo json_encode(array("etat" => "0", "data" => $rest));
    }

    public function asyn_supp_perim_modul() {


        try {

            Model::getDb();
            $tables = new Zend_Db_Table('modul');

            $params = $this->get_passed_vars("data");

            $tabs = $params["perims"];

            foreach ($tabs as $item) {
                $tables->delete("idMODUL = " . $item);
            }

            Message::set_info_msg("Les modules périmé on etais supprimé");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {

            if (!($ex->getMessage() == null)) {
                Message::set_info_msg($ex->getMessage());
                echo json_encode(array("etat" => "1"));
            } else {
                echo json_encode(array("etat" => "0"));
            }
        }
    }

}

?>