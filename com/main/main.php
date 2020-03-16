<?php

class main extends controler {

    public function index() {

        $model = new model();

        view::setContext(get_class($this));

        view::addDefaultsCss();

        view::addDefaultJs();

        view::render("tpl");
    }

    public function asyn_isadmin() {
        if (Zend_Auth::getInstance()->getIdentity()->IDUTIL == 1) {
            echo json_encode(array("etat" => "0", "etats" => "1"));
        } else {
            echo json_encode(array("etat" => "0", "etats" => "2"));
        }
    }

    public function asyn_getautoselect() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        try {

            $qr = $db->select()->distinct()->from(array("A" => $params["model"]), array($params["id"], $params["lib"]))->order($params["lib"] . " DESC");



            $rs = $qr->query();

            $list = $rs->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $list));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_add_entitie() {
        try {

            $db = Model::getDb();

            $params = $this->get_passed_vars("data");

            $data = array(
                $params["lib"] => $params["val"]
            );

            $db->insert($params["model"], $data);

            $qr = $db->select()->distinct()->from(array("A" => $params["model"]), array($params["id"], $params["lib"]))->order($params["lib"] . " DESC");

            $rs = $qr->query();

            $list = $rs->fetchAll();

            echo json_encode(array("etat" => "0", "data" => $list));
        } catch (Exception $ex) {
            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2", "msg" => $ex->getMessage()));
        }
    }

    public function asyn_getautoselectsearch() {



        $model = $this->get_passed_vars("model");
        $id = $this->get_passed_vars("id");
        $lib = $this->get_passed_vars("lib");
        $aff = $this->get_passed_vars("aff");
        $prm = $this->get_passed_vars("r");
        //$dep = $this->get_passed_vars("dep");
        $dep = $_POST["dep"];
        $ids = explode(";", $aff);



        try {

            $db = Model::getDb();

            $rq = $db->select()->from(array("A" => $model), explode(";", $aff));

            if ($dep == "d") {
                
            } else {
                $item = $dep["item"];
                $val = $dep["val"];
                $rq = $rq->Where("$item = ?", $val);
            }



            foreach ($ids as $key => $col) {
                if ($key == 0) {
                    $rq = $rq->Where("$col like ?", "%" . $prm . "%");
                } else {
                    $rq = $rq->orWhere("$col like ?", "%" . $prm . "%");
                }
            }

            $rs = $rq->query()->fetchall();

            foreach ($rs as $key => $item) {
                $rs[$key]["id"] = $rs[$key][$id];
                $rs[$key]["name"] = $rs[$key][$lib];
            }

            echo json_encode(
                    array(
                        "total_count" => count($rs),
                        "incomplete_results" => false,
                        "items" => $rs
                    )
            );
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2", "msg" => $ex->getMessage()));
        }
    }

    public function asyn_deconection() {
        Zend_Auth::getInstance()->clearIdentity();
        echo json_encode(array("etat" => "1"));
    }

    public function asyn_get_profil_det() {

        $idutil = Zend_Auth::getInstance()->getIdentity()->IDUTIL;

        $db = Model::getDb();

        $prf = $db->select()->from("util")->where("IDUTIL = ?", $idutil)->query()->fetchAll();

        echo json_encode(array("etat" => "0", "data" => $prf));
    }

    public function asyn_get_utilog() {


        $db = Model::getDb();

        $idutil = Zend_Auth::getInstance()->getIdentity()->IDUTIL;

        $util1 = $db->select()->from("util")->where("IDUTIL = ?", $idutil)->query()->fetchAll();

        $util = $db->select()->from("util")->where("UTILOG = ?", $_POST["utilog"])->query()->fetchAll();


        if (empty($util)) {
            echo json_encode(true);
        } else {
            if ($util1[0]["UTILOG"] == $_POST["utilog"]) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
    }

    public function asyn_sauv_profil() {
        $params = $this->get_passed_vars("data");
        $db = Model::getDb();

        if ($db->query("update util set UTILOG = '" . $params["UTILOG"] . "' , UTIPWD = '" . md5($params["UTIPWD"]) . "' where IDUTIL = " . $params["IDUTIL"])) {
            Message::set_info_msg("La mise à jours a été effectué avec success");
            echo json_encode(array("etat" => "1"));
        } else {
            Message::set_info_msg("une erreur est survenue merci de réessayer");
            echo json_encode(array("etat" => "1"));
        }
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
        foreach ($exceptions as $key => $item) {
            $exceptions[$key] = $item;
        };
        echo json_encode($exceptions);
        Exceptions::clearException();
    }

    public function asyn_change_exe() {

        $params = $this->get_passed_vars("data");
        controler::setEvirement("exe", $params["exes"]);

        echo json_encode(array("etat" => "0"));
    }

    public function asyn_change_mag() {
        $params = $this->get_passed_vars("data");
        controler::setEvirement("mag", $params["mags"]);
        echo json_encode(array("etat" => "0"));
    }

    public function asyn_get_mag() {

        $db = Model::getDb();

        $mag = $db->select()->from("mag")->query()->fetchAll();

        echo json_encode(array("etat" => "0", "mag" => $mag));
    }

    public function asyn_get_curent_exe() {
        $date = new Zend_Date();
        $year = $date->get("y");
        controler::setEvirement("exe", $year);
        echo json_encode(array("etat" => "0", "exe" => $year));
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