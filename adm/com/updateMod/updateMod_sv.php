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

                    $reflection = new ReflectionMethod($comp, $search);

                    if ($reflection->isPublic()) {

                        if (!in_array($search, $methode_parent)) {

                            if ($comp == "transfere") {
                                echo $search;
                            }
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
                                    'MODULmod' => $mode,
                                    'MODULdcr' => new Zend_Db_Expr('NOW()'),
                                    'MODULtype' => $type
                                );

                                if (!$tables->insert($data)) {
                                    $erreur ++;
                                    Message::set_info_msg("Erreur mise a jours module :" . $search . "dans composant :" . $comp);
                                } else {
                                    $erreur ++;
                                    $modul++;
                                    Message::set_info_msg("Module :" . $search . " dans le composant :" . $comp . " ajouté avec succés");
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($erreur != 0) {
            echo json_encode(array("etat" => "1"));
        } else if ($modul != 0) {
            echo json_encode(array("etat" => "1"));
        } else {
            echo json_encode(array("etat" => "0"));
        }
    }

}

?>