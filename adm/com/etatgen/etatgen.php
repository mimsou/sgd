<?php

class etatgen extends controler {

    private $phpTpl = array();
    private $js = array();
    private $phpControl = "";
    private $prefix = "";
    private $modname = "";
    private $compath = "";
    private $tpmpath = "";
    private $con = "";
    private $sgbd = "";

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
        $this->modname = $params["modname"];

        $this->compath = PATH_ROOT . DS . "com" . DS . $this->modname;
        $this->tplpath = PATH_ROOT . DS . "tpl" . DS . $this->modname;

        $this->con = $params["con"];
        $this->sgbd = $params["sgbd"];

        mkdir($this->tplpath, "0777");
        mkdir($this->compath, "0777");
        mkdir($this->tplpath . DS . "lib", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "js", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "css", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "img", "0777");

        $this->create_php_controler($params["data"]);
        $this->create_js_file($params["data"]);
        $this->create_tpl_file($params["data"]);


        $fp = fopen($this->tplpath . DS . "lib" . DS . "css" . DS . $this->modname . ".css", 'w');
        fwrite($fp, "");
        fclose($fp);

        Message::set_info_msg("Le module " . $this->modname . " a été créé avec succes");
        echo json_encode(array("etat" => "1"));
    }

    private function create_js_file($data) {

        $prefix = substr($this->modname, 0, 3);

        $js = "$(document).ready(function() {" . "\n";
        $js .='$(".tablesorter").livequery(function() {' . "\n";
        $js .='$(this).tablesorter({' . "\n";
        $js .='theme: "bootstrap",' . "\n";
        $js .='widthFixed: false,' . "\n";
        $js .='headerTemplate: "{content} {icon}",' . "\n";
        $js .='widgets: ["uitheme", "zebra"],' . "\n";
        $js .='widgetOptions: {' . "\n";
        $js .='zebra: ["even", "odd"],' . "\n";
        $js .='filter_reset: ".reset"' . "\n";
        $js .='}' . "\n";
        $js .='})' . "\n";
        $js .='});' . "\n";


        $js .='$.contextMenu({' . "\n";
        $js .='selector: "table",' . "\n";
        $js .='callback: function(key, options) {' . "\n";
        $js .='tableToExcel("#" + $(this).attr("id"), "Table Excel");' . "\n";
        $js .='},' . "\n";
        $js .='items: {' . "\n";
        $js .='"Exporter Excel": {name: "Exporter Excel", icon: "edit"},' . "\n";
        $js .='}' . "\n";
        $js .='});' . "\n";

        foreach ($data as $table) {
            foreach ($table["champs"] as $item) {

                if ($item["date"] == "true") {

                    $js .='$("#' . $item["name"] . '").attr("readonly","");' . "\n";

                    $js .='$("#' . $item["name"] . '").datepicker({' . "\n";
                    $js .="dateFormat: 'dd/mm/yy'," . "\n";
                    $js .='changeMonth: true,' . "\n";
                    $js .='changeYear: true' . "\n";
                    $js .='})' . "\n";
                }
            }
        }

        $js .='$("#btn_imp").click(function() {' . "\n";
        $js .='if ($("#data_' . $prefix . '").children().length > 0) {' . "\n";
        $js .='var elma = $("#printarea").clone();' . "\n";
        $js .='elma.find(".noprint").remove();' . "\n";
        $js .='var elmb = $("<div></div>").append(elma);' . "\n";
        $js .='elmb.printThis({' . "\n";
        $js .='importCSS: true,' . "\n";
        $js .='removeInline: false,' . "\n";
        $js .='pageTitle: "Liste"' . "\n";
        $js .='});' . "\n";
        $js .='} else {' . "\n";
        $js .='$.alertMsg("La liste à imprimer est vide", "Impression");' . "\n";
        $js .='}' . "\n";
        $js .='})' . "\n";

        $js .='$("#btn_gen").click(function() {' . "\n";
        $js .='get_' . $prefix . '()' . "\n";
        $js .='})' . "\n";

        $js .='$("#filtre_' . $prefix . '").validate({' . "\n";
        $js .='rules: {' . "\n";
        $js .='}' . "\n";
        $js .='})' . "\n";

        $js .='})' . "\n";

        $js .='function get_' . $prefix . '() {' . "\n";
        $js .='var params = getFromObj("#filtre_' . $prefix . '");' . "\n";
        $js .='get_ajax_data("/' . $this->modname . '/asyn_get_' . $prefix . '", params, "get_' . $prefix . '_pupulate");' . "\n";
        $js .='}' . "\n";

        $js .='function get_' . $prefix . '_pupulate(data) {' . "\n";
        $js .='var datas = data["data"]' . "\n";
        $js .='var html = "";' . "\n";
        $js .='$.each(datas, function(index, elm) {' . "\n";
        $js .='html +=' . "'<tr>'" . "\n";
        foreach ($data as $table) {
            foreach ($table["champs"] as $item) {
                if ($item["afficher"] == "true") {
                    $js .='html +=' . "'<td>'" . ' + elm.' . $item["name"] . ' +' . "'</td>'" . "\n";
                }
            }
        }
        $js .='html += ' . "'" . '</tr>' . "'" . "\n";
        $js .='})' . "\n";
        $js .='$("#data_' . $prefix . '") . html(html);' . "\n";
        $js .='}' . "\n";



        $fp = fopen($this->tplpath . DS . "lib" . DS . "js" . DS . $this->modname . ".js", 'w');
        fwrite($fp, $js);
        fclose($fp);
    }

    private function create_tpl_file($data) {

        $prefix = substr($this->modname, 0, 3);

        $tpl = '';
        $tpl .= '<div class="col-md-10 col-md-offset-1">' . "\n";
        $tpl .= '<div class="page-header">' . "\n";
        $tpl .= '<h1> Etat de ' . $this->modname . ' <small></small></h1>' . "\n";
        $tpl .= '</div>' . "\n";
        $tpl .= '</div>' . "\n";
        $tpl .= '<div class="clearfix"></div>' . "\n";

        $tpl .= "\n";

        $tpl .= '<div class="col-md-12">' . "\n";
        $tpl .= '<nav class="navbar navbar-default" role="navigation">' . "\n";
        $tpl .= '<div class="navbar-header">  <a class="navbar-brand" href="#">Filtre</a></div>' . "\n";
        $tpl .= '<div class="navbar-inner">' . "\n";

        $tpl .= "\n";

        $tpl .= '<form class="navbar-form" id="filtre_' . $prefix . '" action="get_' . $prefix . '">' . "\n";
        $tpl .= '<div class="pull-right" style="display: inline-block">' . "\n";
        $tpl .= '<input type="submit" name="subhide" style="display: none;" />' . "\n";
        $tpl .= '<button type="button" class="btn btn-primary btn-sm" id="btn_gen"  ><span class="glyphicon glyphicon-plus"> </span> Génerer </button>' . "\n";
        $tpl .= '<button type="button" class="btn btn-success btn-sm" id="btn_imp"  ><span class="glyphicon glyphicon-print"> </span> Imprimer </button>' . "\n";
        $tpl .= '</div>' . "\n";
        $tpl .= '<div class="col-md-8">' . "\n";
        $cnt = 0;
        foreach ($data as $table) {
            if ($this->sgbd == "db2") {

                foreach ($table["champs"] as $item) {
                    $cnt++;
                    if (!empty($item["desc"])) {
                        $descchamp = $item["desc"];
                    } else {
                        $descchamp = $item["discription"];
                    }
                    if ($item["search"] == "true") {
                        $tpl .='<div class="col-md-3">' . "\n";
                        $tpl .='<div class="input-group input-group-sm">' . "\n";
                        $tpl .='<span class="input-group-addon">' . $descchamp . ': </span>' . "\n";
                        $tpl .='<input type="text" class="form-control" id="' . $item["name"] . 'R" name="' . $item["name"] . 'R" />' . "\n";
                        $tpl .='</div>' . "\n";
                        $tpl .='</div>' . "\n";
                    }
                }
            } else if ($this->sgbd == "mysql") {

                foreach ($table["champs"] as $item) {
                    $cnt++;
                    if (!empty($item["desc"])) {
                        $descchamp = $item["desc"];
                    } else {
                        $descchamp = $item["name"];
                    }
                    if ($item["search"] == "true") {
                        $tpl .='<div class="col-md-3">' . "\n";
                        $tpl .='<div class="input-group input-group-sm">' . "\n";
                        $tpl .='<span class="input-group-addon">' . $descchamp . ': </span>' . "\n";
                        $tpl .='<input type="text" class="form-control" id="' . $item["name"] . 'R" name="' . $item["name"] . 'R" />' . "\n";
                        $tpl .='</div>' . "\n";
                        $tpl .='</div>' . "\n";
                    }
                }
            }
        }

        $tpl .= '</div>' . "\n";

        $tpl .= "\n";

        $tpl .= '</form>' . "\n";

        $tpl .= "\n";

        $tpl .= '</div>' . "\n";
        $tpl .= '</nav>' . "\n";

        $tpl .= "\n";

        $tpl .= '</div>' . "\n";

        $tpl .= "\n";

        $tpl .= '<div class="col-md-12" id="printarea">' . "\n";

        $tpl .= "\n";

        $tpl .= '<table class="tablesorter table table-bordered table-condensed" id="expexcel">' . "\n";
        $tpl .= '<thead>' . "\n";
        $tpl .= '<tr class="fixedhead">' . "\n";



        $tpl .= '<th colspan="' . $cnt . '"><center><h3>Liste de ' . $this->modname . '</h3></center></th>' . "\n";
        $tpl .= '</tr>' . "\n";
        $tpl .= '<tr class="fixedhead">' . "\n";
        foreach ($data as $table) {
            if ($this->sgbd == "db2") {

                foreach ($table["champs"] as $key => $item) {
                    if (!empty($item["desc"])) {
                        $descchamp = $item["desc"];
                    } else {
                        $descchamp = $item["discription"];
                    }
                    if ($item["afficher"] == "true") {
                        $tpl .= '<th>' . $descchamp . '</th>' . "\n";
                    }
                }
            } else if ($this->sgbd == "mysql") {

                foreach ($table["champs"] as $key => $item) {
                    if (!empty($item["desc"])) {
                        $descchamp = $item["desc"];
                    } else {
                        $descchamp = $item["name"];
                    }
                    if ($item["afficher"] == "true") {
                        $tpl .= '<th>' . $descchamp . '</th>' . "\n";
                    }
                }
            }
        }
        $tpl .= '</thead>' . "\n";
        $tpl .= '<tbody id="data_' . $prefix . '"></tbody>' . "\n";
        $tpl .= '</table>' . "\n";

        $tpl .= '</div>' . "\n";


        $fp = fopen($this->tplpath . DS . 'tpl.php', 'w');
        fwrite($fp, $tpl);
        fclose($fp);
    }

    private function create_php_controler($data) {


        $prefix = substr($this->modname, 0, 3);
            $con = $this->con;
        
        $phpc = "";
        $phpc .= "<?php" . "\n";
        $phpc .= "class $this->modname extends controler {" . "\n";

        $phpc .= "\n";
        $phpc .= "/*************************************************** module $this->modname ******************************************/" . "\n" . "\n";
        $phpc .= "public function  index() {" . "\n";
        $phpc .= "view::setContext(get_class(" . '$this' . "));" . "\n";
        $phpc .= "view::addDefaultsCss();" . "\n";
        $phpc .= "view::addDefaultJs();" . "\n";
        $phpc .= "view::render('tpl');" . "\n";
        $phpc .= "}" . "\n";

        $phpc .= 'private function db2_date($date) {' . "\n";
        $phpc .= '$dates = substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);' . "\n";
        $phpc .= 'return($dates);' . "\n";
        $phpc .= '}' . "\n";



        $phpc .= "public function asyn_get_" . $prefix . "() {" . "\n";
        $phpc .= '$params = $this->get_passed_vars("data");' . "\n";
        foreach ($data as $table) {
            foreach ($table["champs"] as $item) {
                if ($item["search"] == "true") {
                    $phpc .= '$' . $item["name"] . ' = $params["' . $item["name"] . 'R"];' . "\n";
                }
            }
        }
        $phpc .= "try {" . "\n";
        $phpc .= '$db = Model::' . $con . '();' . "\n";

        $phpc .= '$get = $db->select()->from(array("' . $data[0]["alias"] . '" => ' . '"' . $data[0]["base"] . '.' . $data[0]["table"] . '"' . '), array(';
        foreach ($data[0]["champs"] as $item) {
            if ($item["afficher"] == "true") {
                $phpc .= '"' . $item["name"] . '",';
            }
        }
        $phpc .= '));' . "\n";
        foreach ($data as $table) {
            foreach ($table["champs"] as $item) {
                if ($item["search"] == "true") {
                    $phpc .= "if (!empty(" . '$' . $item["name"] . ")) {" . "\n";
                    $phpc .= '$get = $get->where(' . '"' . $item["alias"] . "." . $item["name"] . ' = ?", ' . '$' . $item["name"] . ');' . "\n";
                    $phpc .= "}" . "\n";
                }
            }
        }

        /* join */

        foreach ($data as $key => $table) {
            if ($key != 0) {
                if (!empty($table["jonct"])) {
                    $phpc .= '$get = $get->join(array("' . $table["alias"] . '"=>"' . $table["base"] . "." . $table["table"] . '") , "';
                    foreach ($table["jonct"] as $key => $item) {
                        if ($key == 0) {
                            $phpc .= $item["salias"] . "." . $item["sname"] . "=" . $item["talias"] . "." . $item["tname"];
                        } else {
                            $phpc .= ' AND ' . $item["salias"] . "." . $item["sname"] . "=" . $item["talias"] . "." . $item["tname"];
                        }
                    }

                    $phpc .='" , array(';

                    foreach ($table["champs"] as $item) {
                        if ($item["afficher"] == "true") {
                            $phpc .= '"' . $item["name"] . '",';
                        }
                    }
                    $phpc .='));' . "\n";
                }
            }
        }


        /*         * *************** */

        foreach ($data as $table) {
            foreach ($table["champs"] as $item) {
                if (!empty($item["values"])) {
                    if (strpos($item["type"], 'NUMERIC') == false) {
                        $wr = "'" . $item["values"] . "'";
                    } else {
                        $wr = $item["values"];
                    }

                    $phpc .= '$get = $get->where(' . '"' . $item["alias"] . "." . $item["name"] . ' = ?", ' . $wr . ');' . "\n";
                }
            }
        }
        $phpc .= '$rs = $get->query()->fetchAll();' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "0", "data" => $rs));' . "\n";
        $phpc .= "} catch (Exception " . '$ex) {' . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex->getMessage());' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";



        $phpc .= "}" . "\n";

        $fp = fopen($this->compath . DS . $this->modname . '.php', 'w');
        fwrite($fp, $phpc);
        fclose($fp);
    }

}

?>