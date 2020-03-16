<?php

class crudmaker extends controler {

    private $phpTpl = array();
    private $js = array();
    private $phpControl = "";
    private $prefix = "";
    private $modname = "";
    private $compath = "";
    private $tpmpath = "";

    public function index() {

        view::setContext(get_class($this));

        view::addDefaultsCss();

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
                echo json_encode(array("etat" => "0", "data" => $cols, "table" => $params["table"], "base" => $params["base"]));
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

            $db = Model::getDb();

            $colss = $db->query("DISCRIBE ". $params["base"] .".". $params["table"] )->fetchAll();
            
            $cols = array();

            foreach ($colss as $key => $col) {
                $coll = array(
                     "NAME"=>$col["Field"],
                     "IS_NULLABLE"=>$col["Null"],
                     "TYPE"=>$col["Type"],
                     "DESCRIPTION"=>"Extra"
                );
               

                if ($col["Key"] == "PRI") {
                   $coll["INDEX"] = 1;
                } else {
                   $coll["INDEX"] = 0;
                }
                $cols[$key]=$coll;
                
            }

            if (!empty($cols)) {
                echo json_encode(array("etat" => "0", "data" => $cols, "table" => $params["table"], "base" => $params["base"]));
            } else {
                Message::set_info_msg("Merci de verifier la table indiqué");
                echo json_encode(array("etat" => "1"));
            }
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

        $this->phpControl = "";
        $this->phpControl .= "<?php" . "\n";
        $this->phpControl .= "class $this->modname extends controler {" . "\n";

        mkdir($this->tplpath, "0777");
        mkdir($this->compath, "0777");
        mkdir($this->tplpath . DS . "lib", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "js", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "css", "0777");
        mkdir($this->tplpath . DS . "lib" . DS . "img", "0777");

        foreach ($params["data"] as $inter) {
            $this->create_interface($inter);
        }

        $this->phpControl .= "}" . "\n";

        $fp = fopen($this->compath . DS . $this->modname . '.php', 'w');
        fwrite($fp, $this->phpControl);
        fclose($fp);

        $fp = fopen($this->tplpath . DS . "lib" . DS . "css" . DS . $this->modname . ".css", 'w');
        fwrite($fp, "");
        fclose($fp);

        Message::set_info_msg("Le module " . $this->modname . " a été créé avec succes");
        echo json_encode(array("etat" => "1"));
    }

    private function create_interface($data) {

        $name = $data["intername"];
        $base = $data["base"];
        $table = $data["table"];
        $champs = $data["champs"];

        $this->create_php_controler($name, $base, $table, $champs);
        $this->create_js_file($name, $base, $table, $champs);
        $this->create_tpl_file($name, $base, $table, $champs);
    }

    private function create_js_file($name, $base, $table, $champs) {

        $prefix = substr($name, 0, 3);
        $index = $this->get_index($champs);

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

        foreach ($champs as $item) {

            if ($item["date"] == "true") {

                $js .='$("#' . $item["name"] . '").attr("readonly","");' . "\n";

                $js .='$("#' . $item["name"] . '").datepicker({' . "\n";
                $js .="dateFormat: 'dd/mm/yy'," . "\n";
                $js .='changeMonth: true,' . "\n";
                $js .='changeYear: true' . "\n";
                $js .='})' . "\n";
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
        $js .='pageTitle: "Liste des formateurs"' . "\n";
        $js .='});' . "\n";
        $js .='} else {' . "\n";
        $js .='$.alertMsg("La liste à imprimer est vide", "Impression");' . "\n";
        $js .='}' . "\n";
        $js .='})' . "\n";

//

        $js .='$("#ajout' . $prefix . 'diag").dialog({' . "\n";
        $js .='height: 300,' . "\n";
        $js .='width: 1024,' . "\n";
        $js .='autoOpen: false,' . "\n";
        $js .='buttons: {' . "\n";
        $js .='"Ajouter": function() {' . "\n";
        $js .='$("#' . $prefix . '_ajout").attr("action", "ajout_' . $prefix . '");' . "\n";
        $js .='$("#' . $prefix . '_ajout").submit();' . "\n";
        $js .='},' . "\n";
        $js .='"Modifier": function() {' . "\n";
        $js .='$("#' . $prefix . '_ajout").attr("action", "modif_' . $prefix . '");' . "\n";
        $js .='$("#' . $prefix . '_ajout").submit();' . "\n";
        $js .='},' . "\n";
        $js .='"Annuler": function() {' . "\n";
        $js .='$(this).dialog("close");' . "\n";
        $js .='$(".tipsy").remove();' . "\n";
        $js .='}' . "\n";
        $js .='},' . "\n";
        $js .='close: function(event, ui) {' . "\n";
        $js .='$(".tipsy").remove();' . "\n";
        $js .='},' . "\n";
        $js .='modal: true' . "\n";
        $js .='});' . "\n";

        $js .='$("#filtre_' . $prefix . '").validate({' . "\n";
        $js .='rules: {' . "\n";
        $js .='}' . "\n";
        $js .='})' . "\n";

        $js .='$("#' . $prefix . '_ajout").validate({' . "\n";
        $js .='rules: {' . "\n";
        foreach ($champs as $item) {
            if ($item["modifier"] == "true") {
                $js .= $item["name"] . ': {' . "\n";
                if ($item["required"] == "true") {

                    $js .='required: true,' . "\n";
                }

                if (strpos($item["type"], 'NUMERIC') !== false) {
                    if ($item["date"] != "true") {
                        $js .='number: true' . "\n";
                    }
                }

                $js .='},' . "\n";
            }
        }
        $js .='}' . "\n";
        $js .='})' . "\n";


        $js .='$("#btn_ajout").click(function() {' . "\n";
        $js .='$(".ui-dialog-buttonset button:contains(' . "'" . "Ajouter" . "'" . ')").button().show();' . "\n";
        $js .='$(".ui-dialog-buttonset button:contains(' . "'" . "Modifier" . "'" . ')").button().hide();' . "\n";
        $js .='$("#ajout' . $prefix . 'diag").dialog(' . "'" . "option" . "'" . ', ' . "'" . "title" . "'" . ', ' . "'" . "Ajout  $name " . "'" . ');' . "\n";
        $js .='reset_form_' . $prefix . '();' . "\n";
        $js .='$("#ajout' . $prefix . 'diag").dialog("open");' . "\n";
        $js .='})' . "\n";


//
        $js .='$(".supp_' . $prefix . '").livequery(function() {' . "\n";
        $js .='$(this).click(function() {' . "\n";
        $js .='var elm = $(this);' . "\n";
        $js .='$("<div></div;>").dialog({' . "\n";
        $js .='resizable: false,' . "\n";
        $js .='height: 170,' . "\n";
        $js .='width: 300,' . "\n";
        $js .='buttons: {' . "\n";
        $js .='"Ok": function() {' . "\n";
        $js .='supp_' . $prefix . '(elm);' . "\n";
        $js .='$(this).remove();' . "\n";
        $js .='}, fermer: function(event, ui) {' . "\n";
        $js .='$(this).remove();' . "\n";
        $js .='}' . "\n";
        $js .='},' . "\n";
        $js .='close: function(event, ui) {' . "\n";
        $js .='$(this).remove();' . "\n";
        $js .='},' . "\n";
        $js .='resizable: false,' . "\n";
        $js .='title: "Confirmation de suppression",' . "\n";
        $js .='modal: true' . "\n";
        $js .='}).html("Voulez vous supprimer cet formateur ?");' . "\n";
        $js .='})' . "\n";
        $js .='})' . "\n";

        $js .='$(".modif_' . $prefix . '").livequery(function() {' . "\n";
        $js .='$(this).click(function() {' . "\n";
        $js .='$(".ui-dialog-buttonset button:contains(' . "'" . "Ajouter" . "'" . ')").button().hide();' . "\n";
        $js .='$(".ui-dialog-buttonset button:contains(' . "'" . "Modifier" . "'" . ')").button().show();' . "\n";
        $js .='$("#ajout' . $prefix . 'diag").dialog(' . "'" . "option" . "'" . ', ' . "'" . "title" . "'" . ', ' . "'" . "Modifier $name " . "'" . ');' . "\n";
        $js .='get_info_from_update_' . $prefix . '($(this));' . "\n";
        $js .='})' . "\n";
        $js .='})' . "\n";
        $js .='})' . "\n";

        $js .='function get_info_from_update_' . $prefix . '(param) {' . "\n";
        $js .='var params = {};' . "\n";
        foreach ($index as $ind) {
            $js .='params.' . $ind . ' = param.attr("' . $ind . '");' . "\n";
        }
        $js .='get_ajax_data("/' . $this->modname . '/asyn_get_' . $prefix . '_det", params, "get_' . $prefix . '_det_pupulate");' . "\n";
        $js .='}' . "\n";


        $js .='function get_' . $prefix . '_det_pupulate(data) {' . "\n";
        $js .='var datas = data["data"][0];' . "\n";
        $js .='reset_form_' . $prefix . '();' . "\n";

        foreach ($index as $ind) {
            $js .='$("#' . $ind . 'K").val(datas["' . $ind . '"]);' . "\n";
        }


        foreach ($champs as $item) {

            if ($item["modifier"] == "true") {
                if ($item["date"] != "true") {
                    $js .='$("#' . $item["name"] . '").val(datas["' . $item["name"] . '"]);' . "\n";
                } else {
                    $js .='$("#' . $item["name"] . '").val(dateformat(datas["' . $item["name"] . '"]));' . "\n";
                }
            }
        }
        $js .='$("#ajout' . $prefix . 'diag").dialog("open");' . "\n";
        $js .='}' . "\n";


        $js .='function reset_form_' . $prefix . '() {' . "\n";
        foreach ($champs as $item) {
            $js .='$("#' . $item["name"] . '").val("");' . "\n";
        }
        $js .='}' . "\n";

//
        $js .='function supp_' . $prefix . '(param) {' . "\n";

        $js .='var params = {};' . "\n";

        foreach ($index as $ind) {
            $js .='params.' . $ind . ' = param.attr("' . $ind . '");' . "\n";
        }

        $js .='get_ajax_data("/' . $this->modname . '/asyn_supp_' . $prefix . '", params, "supp_' . $prefix . '_pupulate", param);' . "\n";
        $js .='}' . "\n";

        $js .='function supp_' . $prefix . '_pupulate(data, param) {' . "\n";
        $js .='if (data["etat"] == 1) {' . "\n";
        $js .='param.parents("tr").remove();' . "\n";
        $js .='}' . "\n";
        $js .='}' . "\n";



//

        $js .='function get_' . $prefix . '() {' . "\n";
        $js .='var params = getFromObj("#filtre_' . $prefix . '");' . "\n";
        $js .='get_ajax_data("/' . $this->modname . '/asyn_get_' . $prefix . '", params, "get_' . $prefix . '_pupulate");' . "\n";
        $js .='}' . "\n";

        $js .='function get_' . $prefix . '_pupulate(data) {' . "\n";
        $js .='var datas = data["data"]' . "\n";
        $js .='var html = "";' . "\n";
        $js .='$.each(datas, function(index, elm) {' . "\n";
        $js .='html +=' . "'<tr>'" . "\n";
        foreach ($champs as $item) {
            if ($item["afficher"] == "true") {
                $js .='html +=' . "'<td>'" . ' + elm.' . $item["name"] . ' +' . "'</td>'" . "\n";
            }
        }
        $js .='html += ' . "'<td class=" . '"' . "noprint" . '"' . ">'" . "\n";

        $indxstr = "";
        foreach ($index as $ind) {
            $indxstr .= " " . $ind . '="' . "'" . '+elm.' . $ind . '+' . "'" . '"';
        }

        $js .='html +=' . "'" . '<button class="btn btn-info btn-xs supp_' . $prefix . '"  ' . $indxstr . ' style="display: inline-block;"> Supprimer </button> ' . "'" . "\n";
        $js .='html += ' . "'" . '<button class="btn btn-info btn-xs modif_' . $prefix . '"  ' . $indxstr . ' style="display: inline-block;"> Modifier </button>' . "'" . "\n";
        $js .='html += ' . "'" . '</td></tr>' . "'" . "\n";
        $js .='})' . "\n";
        $js .='$("#data_' . $prefix . '") . html(html);' . "\n";
        $js .='}' . "\n";

        $js .='function ajout_' . $prefix . '() {' . "\n";
        $js .='var params = getFromObj("#' . $prefix . '_ajout");' . "\n";
        $js .='get_ajax_data("/' . $this->modname . '/asyn_ajout_' . $prefix . '", params, "ajout_' . $prefix . '_pupulate");' . "\n";
        $js .='}' . "\n";

        $js .='function ajout_' . $prefix . '_pupulate(data) {' . "\n";
        $js .='$("#ajout' . $prefix . 'diag").dialog("close");' . "\n";
        $js .='}' . "\n";

        $js .='function modif_' . $prefix . '() {' . "\n";
        $js .='var params = getFromObj("#' . $prefix . '_ajout");' . "\n";
        $js .='get_ajax_data("/' . $this->modname . '/asyn_modif_' . $prefix . '", params, "modif_' . $prefix . '_pupulate");' . "\n";
        $js .='}' . "\n";

        $js .='function modif_' . $prefix . '_pupulate(data) {' . "\n";
        $js .='$("#ajout' . $prefix . 'diag").dialog("close");' . "\n";
        $js .='}' . "\n";

        $fp = fopen($this->tplpath . DS . "lib" . DS . "js" . DS . $name . ".js", 'w');
        fwrite($fp, $js);
        fclose($fp);
    }

    private function create_tpl_file($name, $base, $table, $champs) {

        $prefix = substr($name, 0, 3);
        $index = $this->get_index($champs);

        $tpl = '';
        $tpl .= '<div class="col-md-10 col-md-offset-1">' . "\n";
        $tpl .= '<div class="page-header">' . "\n";
        $tpl .= '<h1> Gestion de ' . $name . ' <small></small></h1>' . "\n";
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
        $tpl .= '<button class="btn btn-primary btn-sm" id="btn_chercher"  ><span class="glyphicon glyphicon-search"> </span> Chercher </button>' . "\n";
        $tpl .= '<button type="button" class="btn btn-primary btn-sm" id="btn_ajout"  ><span class="glyphicon glyphicon-plus"> </span> Ajouter un ' . $name . ' </button>' . "\n";
        $tpl .= '<button type="button" class="btn btn-success btn-sm" id="btn_imp"  ><span class="glyphicon glyphicon-print"> </span> Imprimer </button>' . "\n";
        $tpl .= '</div>' . "\n";
        $tpl .= '<div class="col-md-8">' . "\n";
        foreach ($champs as $item) {
            if ($item["search"] == "true") {
                $tpl .='<div class="col-md-3">' . "\n";
                $tpl .='<div class="input-group input-group-sm">' . "\n";
                $tpl .='<span class="input-group-addon">' . $item["discription"] . ': </span>' . "\n";
                $tpl .='<input type="text" class="form-control" id="' . $item["name"] . 'R" name="' . $item["name"] . 'R" />' . "\n";
                $tpl .='</div>' . "\n";
                $tpl .='</div>' . "\n";
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

        $cnt = count($champs);

        $tpl .= '<th colspan="' . $cnt . '"><center><h3>Liste de ' . $name . '</h3></center></th>' . "\n";
        $tpl .= '</tr>' . "\n";
        $tpl .= '<tr class="fixedhead">' . "\n";
        foreach ($champs as $key => $item) {
            if ($item["afficher"] == "true") {
                $tpl .= '<th>' . $item["discription"] . '</th>' . "\n";
            }
        }
        $tpl .= '<th class="noprint" >OPERATION </th>' . "\n";
        $tpl .= '</tr>' . "\n";
        $tpl .= '</thead>' . "\n";
        $tpl .= '<tbody id="data_' . $prefix . '"></tbody>' . "\n";
        $tpl .= '</table>' . "\n";

        $tpl .= '</div>' . "\n";


        $tpl .= '<div id="ajout' . $prefix . 'diag" title="Ajout ' . $name . '" >' . "\n";
        $tpl .= '<form id="' . $prefix . '_ajout" action="ajout_' . $prefix . '">' . "\n";


        $tpl .= "\n";


        $tpl .= '<div class="col-md-12 ">' . "\n";
        $tpl .= '<br/>' . "\n";

        $tpl .= '<div class="col-md-6">' . "\n";
        $tpl .= '<div class="well well-sm entstyle" id="entbeinp">' . "\n";

        foreach ($index as $ind) {
            $tpl .= '<input type="hidden" id="' . $ind . 'K" name="' . $ind . 'K" />';
        }

        foreach ($champs as $key => $item) {

            if ($item["modifier"] == "true") {
                $tpl .= '<div class="input-group input-group-sm">' . "\n";
                $tpl .= '<span class="input-group-addon">' . $item["discription"] . ' : </span>' . "\n";
                $tpl .= '<input type="text" class="form-control impstrong" id="' . $item["name"] . '" name="' . $item["name"] . '" >' . "\n";
                $tpl .= '</div>' . "\n";
                $tpl .= '<br/>' . "\n";
            }

            if ((count($champs) / 2) == $key) {
                $tpl .= '</div>' . "\n";
                $tpl .= '</div>' . "\n";
                $tpl .= '<div class="col-md-6">' . "\n";
                $tpl .= '<div class="well well-sm entstyle" id="entbeinp">' . "\n";
            }
        }
        $tpl .= '</div>' . "\n";
        $tpl .= '</div>' . "\n";



        $tpl .= '</div>' . "\n";

        $tpl .= '</form>' . "\n";
        $tpl .= '</div>' . "\n";

        $fp = fopen($this->tplpath . DS . $name . '.php', 'w');
        fwrite($fp, $tpl);
        fclose($fp);
    }

    private function create_php_controler($name, $base, $table, $champs) {


        $prefix = substr($name, 0, 3);
        $index = $this->get_index($champs);

        $phpc = "\n";
        $phpc .= "/*************************************************** module $name ******************************************/" . "\n" . "\n";
        $phpc .= "public function  $name() {" . "\n";
        $phpc .= "view::setContext(get_class(" . '$this' . "));" . "\n";
        $phpc .= "view::addDefaultsCss();" . "\n";
        $phpc .= "view::addjs(" . '"' . $name . '"' . ");" . "\n";
        $phpc .= "view::render('$name');" . "\n";
        $phpc .= "}" . "\n";

        $phpc .= 'private function db2_date($date) {' . "\n";
        $phpc .= '$dates = substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);' . "\n";
        $phpc .= 'return($dates);' . "\n";
        $phpc .= '}' . "\n";



        $phpc .= "public function asyn_ajout_" . $prefix . "() {" . "\n";
        $phpc .= '$params' . "= " . '$this' . "->get_passed_vars('data');" . "\n";
        $phpc .= "try {" . "\n";
        $phpc .= '$db = Model::getDb2();' . "\n";

        foreach ($champs as $item) {
            if ($item["ai"] == "true") {
                $phpc .= '$' . $item["name"] . "= " . '$db' . "->query('SELECT case when MAX(" . $item["name"] . ")+1 is null then 1 else MAX(" . $item["name"] . ")+1 end as " . $item["name"] . " from $base.$table')->fetchAll();" . "\n";
            }
        }
        $phpc .= '$data' . " = array(" . "\n";
        foreach ($champs as $item) {
            if ($item["ai"] == "true") {
                $phpc .= '"' . $item["name"] . '" => (int) $' . $item["name"] . '[0]["' . $item["name"] . '"],' . "\n";
            } else {
                if ($item["modifier"] == "true") {
                    if ($item["date"] != "true") {
                        $phpc .= '"' . $item["name"] . '" => $params["' . $item["name"] . '"],' . "\n";
                    } else {
                        $phpc .= '"' . $item["name"] . '" => $this->db2_date($params["' . $item["name"] . '"]),' . "\n";
                    }
                }
            }
        }
        $phpc .= ");" . "\n";
        $phpc .= '$db' . '->insert("' . "$base.$table" . '", ' . '$data' . ");" . "\n";
        $phpc .= 'Message::set_info_msg("Ajout effectué avec success");' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "1"));' . "\n";
        $phpc .= "} catch (Exception " . '$ex' . ") {" . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex' . "->getMessage());" . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";

        $phpc .= "public function asyn_get_" . $prefix . "() {" . "\n";
        $phpc .= '$params = $this->get_passed_vars("data");' . "\n";
        foreach ($champs as $item) {
            if ($item["search"] == "true") {
                $phpc .= '$' . $item["name"] . ' = $params["' . $item["name"] . 'R"];' . "\n";
            }
        }
        $phpc .= "try {" . "\n";
        $phpc .= '$db = Model::getDb2();' . "\n";
        $phpc .= '$get = $db->select()->from(array("A" => ' . '"' . "$base.$table" . '"' . '), array("A.*"));' . "\n";
        foreach ($champs as $item) {
            if ($item["search"] == "true") {
                $phpc .= "if (!empty(" . '$' . $item["name"] . ")) {" . "\n";
                $phpc .= '$get = $get->where(' . '"' . $item["name"] . ' = ?", ' . '$' . $item["name"] . ');' . "\n";
                $phpc .= "}" . "\n";
            }
        }
        $phpc .= '$rs = $get->query()->fetchAll();' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "0", "data" => $rs));' . "\n";
        $phpc .= "} catch (Exception " . '$ex) {' . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex->getMessage());' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";

        $phpc .= "public function asyn_supp_" . $prefix . "() {" . "\n";
        $phpc .= '$params = $this->get_passed_vars("data");' . "\n";

        foreach ($index as $ind) {
            $phpc .= '$' . $ind . ' = $params["' . $ind . '"];' . "\n";
        }

        $wherind = "";
        $i = 0;
        foreach ($index as $ind) {
            if ($i == 0) {
                $wherind .= " " . $ind . " = " . '$' . $ind;
            } else {
                $wherind .= " AND " . $ind . " = " . '$' . $ind;
            }
            $i++;
        }



        $phpc .= 'try {' . "\n";
        $phpc .= '$db = Model::getDb2();' . "\n";
        $phpc .= '$frt = $db->delete("' . "$base.$table" . '", "' . $wherind . '"' . ");" . "\n";
        $phpc .= 'Message::set_info_msg("supprimé avec success");' . "\n";
        $phpc .= 'echo json_encode(array("etat" => 1));' . "\n";
        $phpc .= "} catch (Exception " . '$ex) {' . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex->getMessage());' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";

        $phpc .= "public function asyn_get_" . $prefix . "_det() {" . "\n";
        $phpc .= '$params = $this->get_passed_vars("data");' . "\n";
        foreach ($index as $ind) {
            $phpc .= '$' . $ind . ' = $params["' . $ind . '"];' . "\n";
        }
        $phpc .= 'try {' . "\n";
        $phpc .= '$db = Model::getDb2();' . "\n";
        $phpc .= '$get = $db->select()->from(array("A" => "' . "$base.$table" . '"), array("A.*"))->where("' . $wherind . '");' . "\n";
        $phpc .= '$rs = $get->query()->fetchAll();' . "\n";
        $phpc .= 'echo json_encode(array("etat" => "0", "data" => $rs));' . "\n";
        $phpc .= "} catch (Exception " . '$ex) {' . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex->getMessage());' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";



        $phpc .= "public function asyn_modif_" . $prefix . "() {" . "\n";
        $phpc .= '$params = $this->get_passed_vars("data");' . "\n";
        $phpc .= 'try {' . "\n";
        $phpc .= '$db = Model::getDb2();' . "\n";
        foreach ($index as $ind) {
            $phpc .= '$' . $ind . ' = $params["' . $ind . 'K"];' . "\n";
        }
        $phpc .= '$data = array(' . "\n";
        foreach ($champs as $item) {
            if ($item["name"] != $index) {
                if ($item["modifier"] == "true") {
                    if ($item["date"] != "true") {
                        $phpc .= '"' . $item["name"] . '" => $params["' . $item["name"] . '"],' . "\n";
                    } else {
                        $phpc .= '"' . $item["name"] . '" => $this->db2_date($params["' . $item["name"] . '"]),' . "\n";
                    }
                }
            }
        }
        $phpc .= ');';
        $phpc .= '$db->update("' . "$base.$table" . '", $data, "' . $wherind . '"' . ');' . "\n";
        $phpc .= 'Message::set_info_msg("Modification effectué avec success");' . "\n";
        $phpc .= 'echo json_encode(array("etat" => "1"));';
        $phpc .= "} catch (Exception " . '$ex) {' . "\n";
        $phpc .= "Exceptions::setLastException(" . '$ex->getMessage());' . "\n";
        $phpc .= "echo json_encode(" . 'array("etat" => "2"));' . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "}" . "\n";
        $phpc .= "\n";

        $this->phpControl .=$phpc;
    }

    private function get_index($champ) {
        $indexarr = array();
        foreach ($champ as $item) {
            if ($item["index"] == "true") {
                array_push($indexarr, $item["name"]);
            }
        }
        return $indexarr;
    }

}

?>