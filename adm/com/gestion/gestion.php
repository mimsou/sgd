<?php

class gestion extends controler {

    public function index() {

        view::setContext(get_class($this));

        view::addDefaultsCss();

        view::addDefaultJs();

        view::render("tpl");
    }

    public function asyn_get_ouvexe() {
        $db = Model::getDb();
        $max = $db->query("select max(exe) as hh from exe")->fetch();
        $date = new Zend_Date();
        $current = $date->toString("Y");

        if ((int) $current > (int) $max["hh"]) {
            
            $data = array(
                "exe" => $current,
                "idETAT" => 51,
            );
            $db->insert("exe", $data);
             $db->query("update exe set idETAT = 52 where exe !=".$current)->execute();
            echo json_encode(array("etat" => "0"));
        }else{
            Message::set_info_msg("L'ouverture de l'anné exercice ne s'effectue qu'au début de celle-si");
             echo json_encode(array("etat" => "1"));
        }
    }

    /*     * ********************* action service *************************** */

    public function asyn_get_supser() {
        
    }

    public function asyn_get_srvlist() {

        $db = Model::getDb();
        $tables = new Zend_Db_Table('ent');

        try {
            $qr = $tables->select();
            $rs = $qr->query();

            if ($entlist = $rs->fetchAll()) {
                echo json_encode(array("etat" => 0, "data" => $entlist));
            } else {
                Exceptions::setLastException(Exceptions::getExceptionByCode(2001));
            }
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
        }
    }

    /*     * ********************* action profil *************************** */

    public function asyn_aj_prf() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('prf');

        $data = array(
            "PRFnom" => $params["prfnom"],
            "idEnt" => $params["ident"],
            "PRFdcr" => new Zend_Db_Expr('NOW()'),
            "PRFtype" => 2,
            "PRFetat" => 31
        );

        try {
            $tables->insert($data);
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_prf() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('prf');

        try {

            $qr = $tables->select()->where("idENT = ?", $params["ident"]);
            $rs = $qr->query();
            $sprflist = $rs->fetchAll();
            echo json_encode(array("etat" => "0", "data" => $sprflist));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_sup_prf() {

        $params = $this->get_passed_vars("data");

        if ($params["idprf"] != 0) {
            $db = Model::getDb();
            try {

                $db->delete('prf', "idPRF = " . $params["idprf"]);
                Message::set_info_msg("Profil supprimer avec succes");
                echo json_encode(array("etat" => "1"));
            } catch (Exception $ex) {

                Exceptions::setLastException($ex->getMessage() . " " . $params["idprf"]);
                echo json_encode(array("etat" => "2"));
            }
        } else {
            Exceptions::setLastException(Exceptions::getExceptionByCode("1006"));
            echo json_encode(array("etat" => "2"));
        }
    }

    /*     * ********************* action utilisateur *************************** */

    public function asyn_aj_util() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('util');

        $data = array(
            "UTIPNOM" => $params["UTIPNOM"],
            "UTINOM" => $params["UTINOM"],
            "UTILOG" => $params["UTILOG"],
            "UTImat" => $params["UTImat"],
            "UTIPWD" => md5($params["UTIPWD"]),
            "idPRF" => $params["idprf"],
            "UTIDCR" => new Zend_Db_Expr('NOW()'),
            "ETATCOD" => 41
        );

        try {
            $tables->insert($data);
            Message::set_info_msg("Utilisateur ajouté avec succés");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_allutil() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('util');

        try {

            $qr = $tables->select()->where("idPRF = ?", $params["idprf"]);
            $rs = $qr->query();
            $utilist = $rs->fetchAll();
            echo json_encode(array("etat" => "0", "data" => $utilist));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_util() {

        $params = $this->get_passed_vars("data");
        $db = Model::getDb();
        $tables = new Zend_Db_Table('util');

        $data = array(
            "UTIPNOM" => $params["UTIPNOM"],
            "UTINOM" => $params["UTINOM"],
            "UTILOG" => $params["UTILOG"],
            "UTImat" => $params["UTImat"],
            "UTIPWD" => md5($params["UTIPWD"]),
            "UTIDCR" => new Zend_Db_Expr('NOW()'),
            "ETATCOD" => 41
        );

        try {

            $qr = $tables->select()->where("IDUTIL = ?", $params["idutil"]);
            $rs = $qr->query();
            $util = $rs->fetch();
            echo json_encode(array("etat" => "0", "data" => $util));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_ck_login() {

        $login = $this->get_passed_vars("login");
        $db = Model::getDb();

        $valdate = new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'util',
            'field' => 'UTILOG'));

        if ($valdate->isValid($login)) {
            echo json_encode("true");
        } else {
            echo json_encode("Se login existe déja mérci d'en choisir un autre ");
        }
    }

    public function asyn_modif_util() {


        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('util');

        $data = array(
            "UTIPNOM" => $params["UTIPNOM"],
            "UTINOM" => $params["UTINOM"],
            "UTILOG" => $params["UTILOG"],
            "UTImat" => $params["UTImat"],
            "UTIPWD" => md5($params["UTIPWD"]),
            "ETATCOD" => $params["ETATCOD"]
        );

        try {

            $qr = $tables->update($data, "IDUTIL = " . $params["IDUTIL"]);
            Message::set_info_msg("Utilisateur Modifier avec succés");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_sup_util() {


        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        try {

            if ($db->delete('util', "IDUTIL = " . $params["idutil"] . " and idPRF != 0")) {
                Message::set_info_msg("Utilisateur supprimer avec succes");
                echo json_encode(array("etat" => "1"));
            } else {
                Exceptions::setLastException(Exceptions::getExceptionByCode("1007"));
                echo json_encode(array("etat" => "2"));
            }
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage() . " " . $params["idutil"]);
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_autocomplete_user() {

        $login = $this->get_passed_vars("term");

        $db = Model::getDb();

        $tables = new Zend_Db_Table('util');

        $util = $db->query("SELECT `util`.* FROM `util` WHERE (UTILOG like '$login%')")->fetchAll();

        $utilform = array();

        foreach ($util as $item) {

            $arr = array(
                "id" => $item["IDUTIL"],
                "value" => $item["UTILOG"]
            );

            array_push($utilform, $arr);
        }

        echo json_encode($utilform);
    }

    public function asyn_serch_user() {

        $param = $this->get_passed_vars("data");

        $db = Model::getDb();

        $id = $param["idutil"];

        $tables = new Zend_Db_Table('util');

        $util = $db->query("SELECT `util`.idPRF FROM `util` WHERE (IDUTIL = $id)")->fetchAll();

        $idprf = $util[0]["idPRF"];

        $prf = $db->query("SELECT `prf`.idENT FROM `prf` WHERE (idPRF = $idprf)")->fetchAll();

        $ident = $prf[0]["idENT"];

        echo json_encode(array("idprf" => $idprf, "ident" => $ident));
    }

    /*     * **************************** gestion menu ********************************** */

    public function asyn_ajout_menu() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('menu');

        $data = array(
            "MENUnom" => $params["mname"],
            "MENUdcr" => new Zend_Db_Expr('NOW()'),
            "MENUparent" => 0,
        );

        try {
            $tables->insert($data);
            Message::set_info_msg("Menu ajouté avec succés");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_allvu() {


        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        try {

            $qr = $tables->select()->where("MODULtype = ?", "vu")->where("MODULmod = ?", "front")->where("idMENU = ?", "0");
            $rs = $qr->query();
            $modul = $rs->fetchAll();
            $vu = array();
            foreach ($modul as $item) {
                $subarray = array(
                    "id" => $item["idMODUL"],
                    "text" => $item["MODULnom"],
                    "type" => "module"
                );
                array_push($vu, $subarray);
            }
            echo json_encode(array("etat" => "0", "data" => $vu));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_menulist() {

        $db = Model::getDb();
        $tables = new Zend_Db_Table('menu');
        $tableschild = new Zend_Db_Table('modul');
        $submenu = array();
        try {

            $qr = $tables->select()->where("idMENU <> ?", 0)->where("MENUparent = ?", 0)->order("MENUmnseq");
            $rs = $qr->query();
            $mnlist = $rs->fetchAll();
            $menu = array();
            foreach ($mnlist as $item) {
                $submenu = array(
                    "id" => $item["idMENU"],
                    "text" => $item["MENUnom"],
                    "type" => "menu",
                    "children" => $this->_getsubmenu($item["idMENU"])
                );
                array_push($menu, $submenu);
            }
            echo json_encode(array("etat" => "0", "data" => $menu));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    private function _getsubmenu($id) {

        $db = Model::getDb();
        $tables = new Zend_Db_Table('menu');
        $tableschild = new Zend_Db_Table('modul');
        $childmenu = array();
        $subchildmenu = array();

        $qr = $tables->select()->where("MENUparent = ?", $id)->order("MENUmnseq");
        $rs = $qr->query();
        $mnlist = $rs->fetchAll();
        $menu = array();
        foreach ($mnlist as $item) {
            $subchildmenu = array(
                "id" => $item["idMENU"],
                "text" => $item["MENUnom"],
                "type" => "menu",
                "children" => $this->_getsubmenu($item["idMENU"])
            );
            array_push($childmenu, $subchildmenu);
        }


        $submodul = array();
        $qrm = $tableschild->select()->where("idMENU = ?", $id)->where("MODULtype = ?", "vu")->where("MODULmod = ?", "front")->order("MODULmnseq");
        $rsm = $qrm->query();
        $childlist = $rsm->fetchAll();
        foreach ($childlist as $itemm) {
            $submodul = array(
                "id" => $itemm["idMODUL"],
                "text" => $itemm["MODULnom"],
                "type" => "module"
            );
            array_push($childmenu, $submodul);
        }

        return($childmenu);
    }

    public function asyn_menu_remove() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('menu');
        $tableschild = new Zend_Db_Table('modul');

        $qr_md = $tableschild->select()->where("idMENU = ?", $params["idMENU"])->where("MODULtype = ?", "vu")->where("MODULmod = ?", "front");
        $rs_md = $qr_md->query();

        if (count($rs_md->fetchAll()) > 0) {
            Message::set_info_msg("Menu non vide");
            echo json_encode(array("etat" => "1"));
        } else {
            try {
                $tables->delete("idMENU = " . $params["idMENU"]);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        }
    }

    public function asyn_module_cat() {

        $params = $this->get_passed_vars("data");
        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        try {

            $data = array(
                "MODULcat" => $params["catmodule"]
            );

            $id = $params["idmodul"];

            $tables->update($data, "idMODUL = $id");

            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_module_renome() {

        $params = $this->get_passed_vars("data");
        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        try {

            $data = array(
                "MODULnom" => $params["modulnom"]
            );

            $id = $params["idmodul"];
            $tables->update($data, "idMODUL = $id");
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_modif_menu() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        try {

            $data = array(
                "idMENU" => $params["idmenu"]
            );

            $tables->update($data, "idMODUL =  " . $params["idmodul"]);
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_modif_menu_menu() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('menu');

        try {

            $data = array(
                "MENUparent" => $params["idmenu"]
            );

            $tables->update($data, "idMENU =  " . $params["idmodul"]);
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_modif_modul() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        try {

            $data = array(
                "idMENU" => 0
            );

            $tables->update($data, "idMODUL =  " . $params["idmodul"]);
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_ajout_menu_icone() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        if ($params['type'] == "module") {
            $tables = new Zend_Db_Table('modul');
            $col = "idMODUL =  ";
            $data = array(
                "MODULicon" => $params['iconeclass']
            );
        } else if ($params['type'] == "menu") {
            $tables = new Zend_Db_Table('menu');
            $col = "idMENU =  ";
            $data = array(
                "MENUicon" => $params['iconeclass']
            );
        }

        try {

            $tables->update($data, $col . $params["id"]);
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_modif_menu_position() {

        $params = $this->get_passed_vars("data");

        $i = 10;
        foreach ($params as $item) {

            $db = Model::getDb();
            $tables = new Zend_Db_Table('menu');

            $data = array(
                "MENUmnseq" => $i
            );

            $tables->update($data, "idMENU = " . $item["id"]);
            $i++;

            if (!empty($item["children"])) {
                $inc = $i;
                $i = $this->_sub_menu_position($item["children"], $inc);
            }
        }
        echo json_encode(array("etat" => "0"));
    }

    public function _sub_menu_position($params, $inc) {
        $i = $inc;
        foreach ($params as $item) {

            if ($item["type"] == "menu") {



                $db = Model::getDb();
                $tables = new Zend_Db_Table('menu');

                $data = array(
                    "MENUmnseq" => $i
                );

                $tables->update($data, "idMENU = " . $item["id"]);
                $i++;

                if (!empty($item["children"])) {
                    $inc = $i;
                    $i = $this->_sub_menu_position($item["children"], $inc);
                }
            } else if ($item["type"] == "module") {

                $db = Model::getDb();
                $tables = new Zend_Db_Table('modul');

                $data = array(
                    "MODULmnseq" => $i
                );

                $tables->update($data, "idMODUL = " . $item["id"]);
                $i++;
            }
        }
        return $i;
    }

    /*     * ***************************************** droit menu list ******************************************************* */

    public function asyn_get_droit_list() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('droit');

        try {

            $qr = $tables->select()->where("idGRBDROIT = ?", $params["idgrbdroit"]);
            $rs = $qr->query();
            $droit = $rs->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $droit));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_aj_droit() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('droit');

        $data = array(
            "idGRBDROIT" => $params["idgrbdroit"],
            "DROITnom" => $params["droitnom"],
            "DROITdcr" => new Zend_Db_Expr('NOW()'),
        );

        try {
            $tables->insert($data);
            Message::set_info_msg("Droit ajouté avec succés");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_sup_droit() {

        $params = $this->get_passed_vars("data");
        $db = Model::getDb();

        try {

            $db->delete('droit', "idDROIT = " . $params["iddroit"]);
            Message::set_info_msg("Droit supprimer avec succes");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage() . " " . $params["iddroit"]);
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_grbdoit_list() {
        $db = Model::getDb();
        $tables = new Zend_Db_Table('grbdroit');

        try {

            $qr = $tables->select();
            $rs = $qr->query();
            $grbdroit = $rs->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $grbdroit));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_aj_grbdroit() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('grbdroit');

        $data = array(
            "GRBDROITnom" => $params["grbdroitnom"],
            "GRBDROITdcr" => new Zend_Db_Expr('NOW()'),
        );

        try {
            $tables->insert($data);
            Message::set_info_msg("Groupe de droit ajouté avec succés");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_sup_grbdroit() {

        $params = $this->get_passed_vars("data");
        $db = Model::getDb();

        try {

            $db->delete('grbdroit', "idGRBDROIT = " . $params["idgrbdroit"]);
            Message::set_info_msg("Groupe de droit supprimer avec succes");
            echo json_encode(array("etat" => "1"));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage() . " " . $params["idgrbdroit"]);
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_modul_list() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');

        $qr = $db->select()->distinct()->from("modul", "MODULpath")->where("MODULmod = ?", "front");
        $rs = $qr->query();
        $path = $rs->fetchAll();
        $inc = 1;


        $actions = array();
        foreach ($path as $item) {
            $subpath = array(
                "id" => $inc,
                "text" => $item["MODULpath"],
                "type" => "path",
                "state" => array(
                    "disabled" => true
                ),
                "children" => $this->_getactionfrompath($params["iddroit"], $item["MODULpath"]),
            );
            $inc++;
            array_push($actions, $subpath);
        }

        echo json_encode(array("etat" => "0", "data" => $actions));
    }

    private function _getactionfrompath($id, $path) {


        $db = Model::getDb();
        $tables = new Zend_Db_Table('modul');
        $tablesrel = new Zend_Db_Table('droit_has_modul');

        $qr = $tables->select()->where("MODULmod = ?", "front")->where("MODULpath = ?", $path);
        $rs = $qr->query();
        $modul = $rs->fetchAll();

        $submodul = array();
        foreach ($modul as $item) {

            if ($item["MODULtype"] == "vu") {
                $type = "vu";
            } else {
                $type = "action";
            }

            $qr = $tablesrel->select()->where("idMODUL = ?", $item["idMODUL"])->where("idDROIT = ?", $id);
            $rs = $qr->query();
            $modul = $rs->fetchAll();
            if (!empty($modul)) {
                $state = true;
            } else {
                $state = false;
            }




            $par = "(-)";

            switch ($item["MODULcat"]) {
                case "P" : $par = "Paramétrage";
                    break;
                case "C" : $par = "Création";
                    break;
                case "R" : $par = "Lecture";
                    break;
                case "U" : $par = "Modification";
                    break;
                case "D" : $par = "Suppression";
                    break;
            }

            $subm = array(
                "id" => $item["idMODUL"],
                "text" => $item["MODULnom"],
                "type" => $type,
                "data" => array(
                    "cat" => $par
                ),
                "state" => array(
                    "selected" => $state
                )
            );

            array_push($submodul, $subm);
        };

        return $submodul;
    }

    public function asyn_droit_moduls() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('droit_has_modul');

        if ($params["type"] == "select") {



            $data = array(
                "idDROIT" => $params["iddroit"],
                "idMODUL" => $params["idmodul"],
            );

            try {
                $tables->insert($data);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        } else if ($params["type"] == "unselect") {

            try {
                $tables->delete("idDROIT = " . $params["iddroit"] . " and idMODUL = " . $params["idmodul"]);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        }
    }

    /*     * ****************************************************** gestion droit des profils ********************************************** */

    public function asyn_get_all_droits() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('grbdroit');

        $qr = $tables->select();
        $rs = $qr->query();
        $grbdroit = $rs->fetchAll();
        $prentgrbdroit = array();
        foreach ($grbdroit as $item) {
            $subdroit = array(
                "id" => $item["idGRBDROIT"] . "grb",
                "text" => $item["GRBDROITnom"],
                "type" => "grb",
                "state" => array(
                    "disabled" => true
                ),
                "children" => $this->_getsubdroit($item["idGRBDROIT"], $params["idprf"]),
            );
            array_push($prentgrbdroit, $subdroit);
        }

        echo json_encode(array("etat" => "0", "data" => $prentgrbdroit));
    }

    public function asyn_ajout_marqueur() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('droit');

        $data = array(
            "DROITmark" => $params["marqueur"],
        );

        try {
            $tables->update($data, "idDROIT = " . $params["iddroit"]);
            echo json_encode(array("etat" => "0"));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    private function _getsubdroit($id, $idprf) {

        $db = Model::getDb();
        $tables = new Zend_Db_Table('droit');
        $tablesrel = new Zend_Db_Table('prf_has_droit');


        $qr = $tables->select()->where("idGRBDROIT = ?", $id);
        $rs = $qr->query();
        $droit = $rs->fetchAll();

        $subdroit = array();
        foreach ($droit as $item) {

            $qr = $tablesrel->select()->where("idDROIT = ?", $item["idDROIT"])->where("idPRF = ?", $idprf);
            $rs = $qr->query();
            $droiprf = $rs->fetchAll();

            if (!empty($droiprf)) {
                $state = true;
            } else {
                $state = false;
            }
            $subd = array(
                "id" => $item["idDROIT"],
                "text" => $item["DROITnom"],
                "type" => "droit",
                "state" => array(
                    "selected" => $state
                )
            );

            array_push($subdroit, $subd);
        }

        return $subdroit;
    }

    public function asyn_prf_droit() {
        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('prf_has_droit');

        if ($params["type"] == "select") {



            $data = array(
                "idDROIT" => $params["iddroit"],
                "idPRF" => $params["idprf"],
            );

            try {
                $tables->insert($data);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        } else if ($params["type"] == "unselect") {

            try {
                $tables->delete("idDROIT = " . $params["iddroit"] . " and idPRF = " . $params["idprf"]);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        }
    }

    /*     * ****************************************************** gestion mag pour profils ********************************************** */

    public function asyn_get_all_mag() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('mag');
        $tablesrel = new Zend_Db_Table('prf_has_mag');

        $qrmag = $tables->select();
        $rsmag = $qrmag->query();
        $maglist = $rsmag->fetchAll();
        $mag = array();
        foreach ($maglist as $item) {


            $qrrel = $tablesrel->select()->where("MAGCOD = ?", $item["MAGCOD"])->where("idPRF = ?", $params["idprf"]);
            $rsrel = $qrrel->query();
            $droirel = $rsrel->fetchAll();

            if (!empty($droirel)) {
                $state = true;
            } else {
                $state = false;
            }


            $submag = array(
                "id" => $item["MAGCOD"],
                "text" => $item["MAGCOD"] . " => " . utf8_encode($item["MAGLIB"]),
                "type" => "mag",
                "state" => array(
                    "selected" => $state
                )
            );
            array_push($mag, $submag);
        }

        echo json_encode(array("etat" => "0", "data" => $mag));
    }

    public function asyn_mag_prf() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        $tables = new Zend_Db_Table('prf_has_mag');

        if ($params["type"] == "select") {



            $data = array(
                "MAGCOD" => $params["idmag"],
                "idPRF" => $params["idprf"],
            );

            try {
                $tables->insert($data);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        } else if ($params["type"] == "unselect") {

            try {
                $tables->delete("MAGCOD = " . $params["idmag"] . " and idPRF = " . $params["idprf"]);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {
                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        }
    }

    /*     * ****************************************************** gestion exercice ********************************************** */

    public function asyn_get_all_exe() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();
        $tables = new Zend_Db_Table('exe');
        $tablesrel = new Zend_Db_Table('prf_has_exe');

        $qrexe = $tables->select();
        $rsexe = $qrexe->query();
        $exelist = $rsexe->fetchAll();
        $exe = array();



        foreach ($exelist as $item) {

            if ($item["idETAT"] != 51) {

                $qrrel = $tablesrel->select()->where("exe = ?", $item["exe"])->where("idPRF = ?", $params["idprf"]);
                $rsrel = $qrrel->query();
                $droirel = $rsrel->fetchAll();

                if (!empty($droirel)) {
                    $state = true;
                } else {
                    $state = false;
                }


                $subexe = array(
                    "id" => $item["exe"],
                    "text" => $item["exe"],
                    "type" => "exe",
                    "state" => array(
                        "selected" => $state
                    )
                );

                array_push($exe, $subexe);
            } else {

                $subexe = array(
                    "id" => $item["exe"],
                    "text" => $item["exe"] . '<span style="color:green"> En cours <span>',
                    "type" => "exec",
                    "state" => array(
                        "selected" => true,
                        "disabled" => true
                    )
                );

                array_push($exe, $subexe);
            }
        }

        echo json_encode(array("etat" => "0", "data" => $exe));
    }

    public function asyn_exe_prf() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        $tables = new Zend_Db_Table('prf_has_exe');

        if ($params["type"] == "select") {

            $data = array(
                "exe" => $params["idexe"],
                "idPRF" => $params["idprf"],
            );

            try {

                $tables->insert($data);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {

                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        } else if ($params["type"] == "unselect") {

            try {

                $tables->delete("exe = " . $params["idexe"] . " and idPRF = " . $params["idprf"]);
                echo json_encode(array("etat" => "0"));
            } catch (Exception $ex) {

                Exceptions::setLastException($ex->getMessage());
                echo json_encode(array("etat" => "2"));
            }
        }
    }

    /*     * ********************************* historique utilisateur ******************************************** */

    public function asyn_get_hist_action() {

        $params = $this->get_passed_vars("data");
        $util = $params["idutil"];
        $datedeb = $params["datedeb"];
        $datefin = $params["datefin"];
        $descriptOPER = $params["descriptOPER"];


        $db = Model::getDb();

        try {

            $oper = $db->select()->from("oper");

            if (!empty($util)) {
                $oper = $oper->where("IDUTIL = ?", $util);
            }

            if (!empty($datedeb)) {
                $oper = $oper->where("DATEoper >= ?", $datedeb . " 00:00:00");
            }

            if (!empty($datefin)) {
                $oper = $oper->where("DATEoper <= ?", $datefin . " 00:00:00");
            }

            if (!empty($descriptOPER)) {
                $oper = $oper->where("descriptOPER like ?", "%" . $descriptOPER . "%");
            }

            $rst = $oper->order("DATEoper DESC")->query()->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $rst));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_hist_action_ftr() {

        $params = $this->get_passed_vars("data");
        $droit = $params["droit"];
        $datedeb = $params["datedebutil"];
        $datefin = $params["datefinutil"];
        $descriptOPER = $params["descriptOPERF"];
        $paramOPER = $params["paramOPER"];
        $catOPER = $params["modulecathist"];


        $db = Model::getDb();

        try {

            $oper = $db->select()->from("oper");

            if (!empty($droit)) {
                $oper = $oper->where("idMODUL in ?", $db->select()->from("droit_has_modul", array("idMODUL"))->where("idDROIT = ?", $droit));
            }


            if (!empty($datedeb)) {
                $oper = $oper->where("DATEoper >= ?", $datedeb . " 00:00:00");
            }

            if (!empty($datefin)) {
                $oper = $oper->where("DATEoper <= ?", $datefin . " 00:00:00");
            }

            if (!empty($descriptOPERF)) {
                $oper = $oper->where("descriptOPER like ?", "%" . $descriptOPERF . "%");
            }

            if (!empty($paramOPER)) {
                $oper = $oper->where("paramOPER like ?", "%" . $paramOPER . "%");
            }

            if (!empty($catOPER)) {
                $oper = $oper->where("catOPER like ?", $catOPER);
            }

            $rst = $oper->order("DATEoper DESC")->query()->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $rst));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

}

?>