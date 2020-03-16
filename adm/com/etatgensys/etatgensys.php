<?php

class etatgensys extends controler {

    public function index() {

        view::setContext(get_class($this));

        view::addDefaultsCss();
        view::addJs("jsBezier-0.6");
        view::addJs("mottle-0.4");
        view::addJs("biltong-0.2");
        view::addJs("util");
        view::addJs("browser-util");
        view::addJs("dom-adapter");
        view::addJs("jsPlumb");
        view::addJs("endpoint");
        view::addJs("connection");
        view::addJs("anchors");
        view::addJs("defaults");
        view::addJs("connectors-bezier");
        view::addJs("renderers-svg");
        view::addJs("renderers-vml");
        view::addJs("base-library-adapter");
        view::addJs("jquery.jsPlumb");
        view::addDefaultJs();

        view::render("tpl");
    }
    
    public function asyn_get_config_etat($stat = false) {

        try {

            $params = $this->get_passed_vars("data");
            if ($stat) {
                $idetat = $stat;
            } else {
                $idetat = $params["idETAT"];
            }

            $db = Model::getDb();

            $etatrs = $db->select()->from("etatgen")->where("idETAT = ?", $idetat)->query()->fetchAll();

            $etat = json_decode($etatrs[0]["ETAparam"],true);

            if (!$stat) {
                echo json_encode(array("etat" => "0", "data" => $etat));
            } else {
                return $etat;
            }
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());

            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_shema_db2() {

        try {

            $params = $this->get_passed_vars("data");

            $db = Model::getDb2();

            $cols = $db->query("SELECT 
                                 COLUMN_NAME AS Name, 
                                 COLUMN_TEXT AS Description, 
                                 DATA_TYPE AS TYPE, 
                                 LENGTH AS Length, 
                                 NUMERIC_SCALE AS Scale, 
                                 NUMERIC_PRECISION AS PRECISION, 
                                 IS_NULLABLE AS Nullable 
                                FROM 
                                QSYS2.SYSCOLUMNS 
                                WHERE 
                                 TABLE_SCHEMA = '" . $params["base"] . "' AND 
                                 TABLE_NAME = '" . $params["table"] . "'  ORDER BY ORDINAL_POSITION ASC")->fetchAll();


            $rsindex = $db->query("SELECT COLUMN_NAMES FROM QSYS2.SYSTABLEINDEXSTAT WHERE TABLE_SCHEMA = '" . $params["base"] . "' and TABLE_NAME = '" . $params["table"] . "'")->fetchAll();

            $indexstr = $rsindex[0]["COLUMN_NAMES"];

            $indexs = explode(",", $indexstr);

            $indstat = false;

            foreach ($cols as $key => $col) {

                foreach ($indexs as $ind) {
                    $inds = trim($ind);
                    if (trim($col["NAME"]) == $inds) {
                        $indstat = true;
                    }
                }

                if ($indstat == true) {
                    $cols[$key]["INDEX"] = 1;
                } else {
                    $cols[$key]["INDEX"] = 0;
                }

                $indstat = false;
            }

            if (!empty($cols)) {
                foreach ($cols as $key => $item) {
                    $cols[$key]["DESCRIPTION"] = utf8_encode($item["DESCRIPTION"]);
                }
                echo json_encode(array("etat" => "0", "data" => $cols, "table" => $params["table"], "base" => $params["base"], "sgbd" => "db2", "alias" => $params["alias"]));
            } else {
                Message::set_info_msg("Merci de verifier la table indiqué");
                echo json_encode(array("etat" => "1"));
            }
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_shema_mysql() {

        try {

            $params = $this->get_passed_vars("data");

            $con = $params["con"];

            $db = Model::$con();

            $colss = $db->query("DESCRIBE " . $params["base"] . "." . $params["table"])->fetchAll();

            $cols = array();

            foreach ($colss as $key => $col) {
                $coll = array(
                    "NAME" => $col["Field"],
                    "IS_NULLABLE" => $col["Null"],
                    "TYPE" => $col["Type"],
                    "DESCRIPTION" => "Extra"
                );


                if ($col["Key"] == "PRI") {
                    $coll["INDEX"] = 1;
                } else {
                    $coll["INDEX"] = 0;
                }
                $cols[$key] = $coll;
            }


            if (!empty($cols)) {
                echo json_encode(array("etat" => "0", "data" => $cols, "table" => $params["table"], "base" => $params["base"], "sgbd" => "mysql", "alias" => $params["alias"]));
            } else {
                Message::set_info_msg("Merci de verifier la table indiqué");
                echo json_encode(array("etat" => "1"));
            }
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());
            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_base_mysql() {


        try {

            $params = $this->get_passed_vars("data");

            $con = $params["con"];
            $con = trim($con);
            $db = Model::$con();

            $database = $db->query("show databases")->fetchAll();

            echo json_encode(array("etat" => "0", "database" => $database));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());

            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_table_mysql() {


        try {

            $params = $this->get_passed_vars("data");

            $database = $params["database"];

            $con = $params["con"];

            $db = Model::$con();

            $table = $db->query("select table_name from information_schema.tables where table_schema='$database';")->fetchAll();

            echo json_encode(array("etat" => "0", "table" => $table));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());

            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_get_table_column_mysql() {


        try {

            $params = $this->get_passed_vars("data");

            $con = $params["con"];

            $db = Model::$con();

            $colss = $db->query("DESCRIBE " . $params["database"] . "." . $params["table"])->fetchAll();

            echo json_encode(array("etat" => "0", "column" => $colss));
        } catch (Exception $ex) {

            Exceptions::setLastException($ex->getMessage());

            echo json_encode(array("etat" => "2"));
        }
    }

    public function asyn_compile() {

        $params = $this->get_passed_vars("data");

        $db = Model::getDb();

        $data = array(
            "idETAT" => NULL,
            "ETAdesc" => $params["modname"],
            "ETAparam" => json_encode($params),
            "ETAdatecr" => new Zend_Db_Expr("NOW()")
        );

        $db->insert("etatgen", $data);

        Message::set_info_msg("Le module " . $params["modname"] . " a été créé avec succes");

        echo json_encode(array("etat" => "1"));
        
    }

}

?>