var iduser = 0;

$(document).ready(function() {

    $("#param_util").hide("slow");

    /******************************** tree init ***************************************/


    /********************************inti tab ******************************************/
    $("#ouvexe").click(function(){
        $.confirm("Voulez vous vraiment ouvrire un exercice , cela est une action permanante",function(){
          get_ajax_data("/gestion/asyn_get_ouvexe", {}, function(){
              get_exe()
          });  
        })
          
    })

    $('#tabs').tab();


    /******************************** inti gesttion menu  ***********************************/

    get_vu_list();
    get_menu_list();
    get_grbdroit_list();

    /******************************** Validation formulaire ***********************************/

    $("#frm_aj_util").validate({
        rules: {
            UTImat: {
                number: true,
                maxlength: 5
            },
            UTIPWD2: {
                equalTo: "#UTIPWD"
            }
        },
        message: {
            UTImat: {
                number: "Obligatoirment un nombre",
                maxlength: "pas plus de 5 chiffre"
            },
            UTIPWD2: {
                equalTo: "#mot de passe non identique"
            }

        }
    });

    /********************************init dialog***********************************/
    $("#dg_aj_prf").dialog({
        resizable: false,
        height: 200,
        width: 300,
        modal: false,
        autoOpen: false,
        buttons: {
            "ajouter": function() {
                ajout_profil();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        }
    })



    $("#dg_aj_grbdroit").dialog({
        resizable: false,
        height: 200,
        width: 300,
        modal: false,
        autoOpen: false,
        buttons: {
            "ajouter": function() {
                ajout_grbdroit();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        }
    })

    $("#dg_aj_droit").dialog({
        resizable: false,
        height: 200,
        width: 300,
        modal: false,
        autoOpen: false,
        buttons: {
            "ajouter": function() {
                ajout_droit();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        }
    })


    $("#dg_aj_menu").dialog({
        resizable: false,
        height: 200,
        width: 300,
        modal: false,
        autoOpen: false,
        buttons: {
            "ajouter": function() {
                ajout_menu();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        }
    })


    $("#dg_aj_util").dialog({
        resizable: false,
        height: 480,
        width: 700,
        modal: false,
        autoOpen: false,
        buttons: {
            "Sauvegarder": function() {
                $("#frm_aj_util").submit();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        }
    });

    /********************************Init boutton***********************************/

    $(".gestspes").click(function() {

        $("#param_util").show("slow");

    })

    $(".gestgen").click(function() {
        $("#param_util").hide("slow");
    })


    $("#btn_aj_menu").click(function() {
        $("#dg_aj_menu").dialog("open");
    })

    $("#btn_aj_prf").click(function() {
        $("#dg_aj_prf").dialog("open");
    })

    $("#btn_rc_util").click(function() {

        var elm = $("<div></div>").dialog({
            resizable: false,
            height: 170,
            width: 300,
            close: function(event, ui) {
                $(this).remove();
            },
            resizable: false,
                    title: "Recherche utilisateur",
            modal: true
        }).html('<div class="col-lg-9 col-lg-offset-2"><div class="input-group input-group-sm top1"><span class="input-group-addon">Login :</span> <input type="text" class="form-control" name="logutil" id="logutil" /></div></div>');

        $("#logutil").autocomplete({
            method: 'POST',
            source: function(request, response) {
                $.ajax({
                    url: path_base + "/gestion/asyn_autocomplete_user",
                    data: request,
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                search_util(ui.item.id);
                elm.remove();
            }
        });
    })

    $("#btn_aj_droit").click(function() {
        idgrbdroit = $("#grbdroit").val();
        if (idgrbdroit != null) {
            $("#dg_aj_droit").dialog("open");
        } else {
            $.alertMsg("Veuillez selectioné un groupe", "Message")
        }

    })

    $("#btn_aj_mark").click(function() {
        iddroit = $("#droit").val();
        if (iddroit != null) {
            $("<div></div>").dialog({
                resizable: false,
                height: 170,
                width: 300,
                buttons: {
                    "Ok": function() {
                        var param = {};
                        param.iddroit = $("#droit").val();
                        param.marqueur = $("#marqueur").livequery(function() {
                        }).val();
                        get_ajax_data("/gestion/asyn_ajout_marqueur", param, false);
                        $(this).dialog("close");
                    }
                },
                close: function(event, ui) {
                    $(this).remove();
                },
                resizable: false,
                        title: "crée un Marqueur",
                modal: true
            }).html('<div class="col-lg-9 col-lg-offset-2"><div class="input-group input-group-sm top1"><span class="input-group-addon">Marqueur :</span> <input type="text" class="form-control" name="marqueur" id="marqueur" /></div></div>');
        } else {
            $.alertMsg("Veuillez selectioné un droit", "Message")
        }
    })


    $("#btn_sup_droit").click(function() {
        iddroit = $("#droit").val();
        if (iddroit != null) {
            sup_droit();
        } else {
            $.alertMsg("Veuillez selectioné un droit", "Message")
        }
    })

    $("#btn_sup_grbdroit").click(function() {
        iddroit = $("#grbdroit").val();
        if (iddroit != null) {
            sup_grbdroit();
        } else {
            $.alertMsg("Veuillez selectioné un groupe de droits", "Message")
        }
    })



    $("#btn_aj_grbdroit").click(function() {
        $("#dg_aj_grbdroit").dialog("open");
    })


    $("#btn_modif_util").click(function() {
        idutil = $("#util").val();
        if (idutil != null) {
            //$("#UTILOG").rules("remove", "remote");
            prepare_modif_util();
        } else {
            $.alertMsg("Veuillez selectioné un profil", "Message")
        }
    })


    $("#btn_aj_util").click(function() {
        idprf = $("#prf").val();
        if (idprf != null) {
            $("#UTILOG").rules("add", {
                remote: {
                    url: path_base + "/gestion/asyn_ck_login",
                    type: "post",
                    data: {
                        login: function() {
                            return $("#UTILOG").val();
                        }
                    }
                }
            });
            $("#frm_aj_util").attr("action", "ajout_util");
            $("#dg_aj_util").dialog("open");
        } else {
            $.alertMsg("Veuillez selectioné un profil", "Message")
        }
    })

    $("#ent").select2({
        width: "resolve",
        allowClear: true
    }).change(function() {
        get_profil();
    })



    $("#prf").select2({
        width: "resolve",
        allowClear: true
    }).change(function() {
        $("#droit_affect_title").text("Affectation des droit pour : " + $("#prf option:selected").text());
        $("#mag_affect_title").text("Affectation des magasin pour : " + $("#prf option:selected").text());
        get_util();
        get_droits();
        get_mag();
        get_exe();
    })

    $("#grbdroit").select2({
        width: "resolve",
        allowClear: true
    }).change(function() {
        get_droit_list();
    });


    $("#util").select2({
        width: "resolve",
        allowClear: true
    });

    $("#droit").select2({
        width: "resolve",
        allowClear: true
    }).change(function() {
        get_moduls();
    });


    $("#btn_aff_hist").click(function() {
        get_action_historique();
    });

    $("#btn_aff_hist_ftr").click(function() {
        $("#hist_util_filt_diag").dialog("open");
    });



    $("#btn_sup_prf").click(function() {
        idprf = $("#prf").val();
        if (idprf != null) {
            sup_prf();
        } else {
            $.alertMsg("Veuillez selectioné un profil", "Message")
        }
    })

    $("#btn_sup_util").click(function() {
        idutil = $("#util").val();
        if (idutil != null) {
            sup_util();
        } else {
            $.alertMsg("Veuillez selectioné un utilisateur", "Message")
        }
    })


    /******************************init donnée**************************************/


    get_srv_list();


    /********************************** Historique utilisateur **************************/
    $("#frm_hist_util_filt").validate({
        rules: {
            droit: {
            }
        }
    })

    $("#hist_util_filt_diag").dialog({
        resizable: false,
        height: 550,
        width: 600,
        autoOpen: false,
        buttons: {
            "rechercher": function() {
                $("#frm_hist_util_filt").submit();
            },
            "quitter": function() {
                $(this).dialog("close");
            }
        },
        close: function(event, ui) {
            $(this).dialog("close");
        },
        title: "Recherche avancé",
        modal: true
    })

    $("#datedebutil").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    }).mask("9999-99-99");

    $("#datefinutil").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    }).mask("9999-99-99");

})

/******************************* Gestion historique utilisateur **************/


function get_hist_utilisateur_filtre() {

    var param = {};

    param = getFromObj("#frm_hist_util_filt")

    if ($("#selecteduser").is(":checked")) {
        param.idutil = $("#util").val();
    } else {
        param.idutil = "";
    }

    get_ajax_data("/gestion/asyn_get_hist_action_ftr", param, "hist_action_ftr_callback");

}


function hist_action_ftr_callback(data) {

    var hist = data["data"];

    var html = ""

    $.each(hist, function(index, value) {

        html += '<tr>';
        html += '<td>   ' + value.idOPER + '  </td>';
        html += '<td>   ' + value.idMODUL + '  </td>';
        html += '<td>   ' + value.actionOPER + '  </td>';
        html += '<td>   ' + value.descriptOPER + '  </td>';
        html += '<td>  ' + value.dateOPER + '  </td>';
        html += '<td>  ' + value.UTINOM + " " + value.UTIPNOM + '</td>';
        html += '<td>  ' + value.idPRF + '  </td>';
        html += '<td ><div style="width:200px !important;overflow:scroll;">' + value.paramOPER + '</div></td>';
        html += '</tr>';

    })

    $("#data_result").html(html);

}

function get_action_historique() {
    var param = {};
    if ($("#selecteduser").is(":checked")) {
        param.idutil = $("#util").val();
    } else {
        param.idutil = "";
    }
    param.idmodul = $("#idMODUL").val();
    param.descriptOPER = $("#descriptOPER").val();
    param.datedeb = $("#datedebutil").val();
    param.datefin = $("#datefinutil").val();
    get_ajax_data("/gestion/asyn_get_hist_action", param, "hist_action_callback");
}

function hist_action_callback(data) {
    var hist = data["data"];
    var html = ""

    $.each(hist, function(index, value) {


        html += '<tr>';
        html += '<td>   ' + value.idOPER + '  </td>';
        html += '<td>   ' + value.idMODUL + '  </td>';
        html += '<td>   ' + value.actionOPER + '  </td>';
        html += '<td>   ' + value.descriptOPER + '  </td>';
        html += '<td>  ' + value.dateOPER + '  </td>';
        html += '<td>  ' + value.UTINOM + " " + value.UTIPNOM + '</td>';
        html += '<td>  ' + value.idPRF + '  </td>';
        html += '<td ><div style="width:200px !important;overflow:scroll;">' + value.paramOPER + '</div></td>';
        html += '</tr>';

    })


    $("#data_result").html(html);
}

/******************************* Gestion service *****************************/


function get_srv_list() {
    get_ajax_data("/gestion/asyn_get_srvlist", null, "srvlist_pupulate");
}

function srvlist_pupulate(data) {
    var srvlis = data["data"];
    var html = ""

    $.each(srvlis, function(index, data) {
        $("#ent").append("<option value='" + data["idENT"] + "'>" + data["ENTnom"] + "</option>");
    })

    $("#ent").select2("val", $("#ent").val())
    get_profil();
}




/******************************* Gestion profil *****************************/


function ajout_profil() {
    var param = {};
    param.prfnom = $("#PRFnom").val();
    param.ident = $("#ent").val();
    if (param.ident != null) {
        get_ajax_data("/gestion/asyn_aj_prf", param, "aj_prf_callback");
    }
}

function aj_prf_callback(data) {
    if (data["etat"] == 1) {
        $("#dg_aj_prf").dialog("close");
        get_profil();
    }
}

function get_profil() {
    var param = {};
    param.ident = $("#ent").val();
    if (param.ident != null) {
        get_ajax_data("/gestion/asyn_get_prf", param, "prflist_populate");
    } else {
        get_droits();
        get_mag();
        get_util();
        get_exe();
        $("#prf").html("");
    }
}


function prflist_populate(data) {

    var prflis = data["data"];
    $("#prf").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#prf").append("<option value='" + data["idPRF"] + "'>" + data["PRFnom"] + "</option>");
    })

    $("#droit_affect_title").text("Affectation des droit pour : " + $("#prf option:selected").text());
    $("#mag_affect_title").text("Affectation des magasin pour : " + $("#prf option:selected").text());

    $("#prf").select2("val", $("#prf").val());

    get_util();
    get_droits();
    get_mag();
    get_exe();

}


function sup_prf() {

    $.confirm(
            "voulez vous supprimer le profil selectioné ?",
            function() {
                var param = {};
                param.idprf = $("#prf").val();
                get_ajax_data("/gestion/asyn_sup_prf", param, "get_profil");
            })

}

/******************************* Gestion utilisateur *****************************/


function ajout_util() {

    var params = getFromObj("#frm_aj_util");
    params.idprf = $("#prf").val();
    if (params.idprf != null) {
        get_ajax_data("/gestion/asyn_aj_util", params, "aj_util_callback");
    } else {
        $.alertMsg("Veuillez selectioné un profil", "Message")
    }
}

function prepare_modif_util() {

    var params = getFromObj("#frm_aj_util");
    params.idutil = $("#util").val();
    if (params.idutil != null) {
        get_ajax_data("/gestion/asyn_get_util", params, "populate_modifuser");
    } else {
        $.alertMsg("Veuillez selectioné un utilisateur", "Message")
    }

}

function populate_modifuser(data) {
    datas = data["data"];
    $("#frm_aj_util").attr("action", "modif_util");
    $("#UTIPNOM").val(datas["UTIPNOM"]);
    $("#UTINOM").val(datas["UTINOM"]);
    $("#UTILOG").val(datas["UTILOG"]);
    $("#UTImat").val(datas["UTImat"]);
    $("#IDUTIL").val(datas["IDUTIL"]);
    $("#dg_aj_util").dialog("open");
}

function modif_util() {
    var params = getFromObj("#frm_aj_util");
    get_ajax_data("/gestion/asyn_modif_util", params, "aj_util_callback");

}

function aj_util_callback(data) {
    if (data.etat == 1) {
        get_util();
        $("#dg_aj_util").dialog("close");
    }
}


function  sup_util() {

    $.confirm(
            "voulez vous supprimer l'utilisateur selectioné ?",
            function() {
                var param = {};
                param.idutil = $("#util").val();
                get_ajax_data("/gestion/asyn_sup_util", param, "get_util");
            })

}

function get_util() {
    var param = {};
    param.idprf = $("#prf").val();
    if (param.idprf != null) {
        get_ajax_data("/gestion/asyn_get_allutil", param, "utilist_populate");
    } else {
        $("#util").html("");
        $("#util").select2("val", $("#util").val());
    }
}


function utilist_populate(data) {

    var prflis = data["data"];
    $("#util").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#util").append("<option value='" + data["IDUTIL"] + "'>" + data["UTIPNOM"] + "  " + data["UTINOM"] + "</option>");
    })
    $("#util").select2("val", $("#util").val());


}

function search_util(id) {

    var param = {};
    iduser = id;
    param.idutil = id;
    get_ajax_data("/gestion/asyn_serch_user", param, "search_profil");

}

function search_profil(data) {

    $("#ent").select2("val", data["ident"]);

    var param = {};
    param.ident = $("#ent").val();
    if (param.ident != null) {
        get_ajax_data("/gestion/asyn_get_prf", param, "prflist_populate_rc", data["idprf"]);
    } else {
        get_droits();
        get_mag();
        get_util();
        get_exe();
        $("#prf").html("");
    }
}


function prflist_populate_rc(data, idprf) {

    var prflis = data["data"];
    $("#prf").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#prf").append("<option value='" + data["idPRF"] + "'>" + data["PRFnom"] + "</option>");
    })

    $("#droit_affect_title").text("Affectation des droit pour : " + $("#prf option:selected").text());
    $("#mag_affect_title").text("Affectation des magasin pour : " + $("#prf option:selected").text());

    $("#prf").select2("val", idprf);

    set_util();
    get_droits();
    get_mag();
    get_exe();

}

function set_util() {
    var param = {};
    param.idprf = $("#prf").val();
    if (param.idprf != null) {
        get_ajax_data("/gestion/asyn_get_allutil", param, "utilist_populate_rc");
    } else {
        $("#util").html("");
        $("#util").select2("val", $("#util").val());
    }
}


function utilist_populate_rc(data) {

    var prflis = data["data"];
    $("#util").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#util").append("<option value='" + data["IDUTIL"] + "'>" + data["UTIPNOM"] + "  " + data["UTINOM"] + "</option>");
    })
    $("#util").select2("val", iduser);


}


/************************************************** gestion menu **************************************************/

function  ajout_menu() {
    var params = {};
    params.mname = $("#MENUnom").val();
    get_ajax_data("/gestion/asyn_ajout_menu", params, "get_menu_callback");
}

function get_menu_callback() {
    $("#dg_aj_menu").dialog("close");
    get_menu_list();
}

function  get_vu_list() {
    get_ajax_data("/gestion/asyn_get_allvu", null, "create_vu_arborescence");
}

function create_vu_arborescence(data) {

    $("#gst_menu_modul").jstree('destroy');
    var vu = data["data"];
    $("#gst_menu_modul").jstree({
        'core': {
            'data': vu,
            "check_callback": true
        },
        "contextmenu": {
            "items": function($node) {
                var tree = $("#gst_menu_modul").jstree(true);
                return {
                    "Rename": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Rename",
                        "action": function(obj) {
                            tree.edit($node);
                        }
                    }

                };
            }
        },
        'types': {
            'module': {
                icon: "glyphicon glyphicon-list-alt",
                'max_children': 0
            }
        },
        'plugins': ["contextmenu", "dnd", "crrm", "types"]
    }).bind("rename_node.jstree", function(event, data) {
        var params = {};
        params.modulnom = data.text;
        params.idmodul = data.node.id;
        get_ajax_data("/gestion/asyn_module_renome", params, "refresh_all_tree");
    }).bind("copy_node.jstree", function(e, data) {
        var params = {};
        params.idmodul = data.original.id;
        get_ajax_data("/gestion/asyn_modif_modul", params, "refresh_all_tree");
    });




}


function get_menu_list() {
    get_ajax_data("/gestion/asyn_get_menulist", null, "create_menu_arborescence");
}

function create_menu_arborescence(data) {

    var menu = data["data"];
    $("#gst_modul_menu").jstree('destroy');
    $("#gst_modul_menu").jstree({
        'core': {
            'data': menu,
            "check_callback": true
        },
        'types': {
            'menu': {
                'icon': "glyphicon glyphicon-plus",
                'max_depth': 3
            },
            'module': {
                'icon': "glyphicon glyphicon-list-alt"
            }

        },
        "contextmenu": {
            "items": function($node) {
                var tree = $("#gst_modul_menu").jstree(true);
                return {
                    "Renomer": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Rename",
                        "action": function(obj) {
                            tree.edit($node);
                        }
                    },
                    "Ajouter une icone": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Icon",
                        "action": function(obj) {
                            $("<div></div>").dialog({
                                resizable: false,
                                height: 170,
                                width: 300,
                                buttons: {
                                    "Ok": function() {
                                        var param = {};
                                        param.iconeclass = $("#iconeclass").livequery(function() {
                                        }).val();
                                        param.id = $node.id;
                                        param.type = $node.type;
                                        get_ajax_data("/gestion/asyn_ajout_menu_icone", param, false);
                                        $(this).dialog("close");
                                    }
                                },
                                close: function(event, ui) {
                                    $(this).remove();
                                },
                                resizable: false,
                                        title: "Ajouter une icone",
                                modal: true
                            }).html('<div class="col-lg-9 col-lg-offset-2"><div class="input-group input-group-sm top1"><span class="input-group-addon">Class :</span> <input type="text" class="form-control" name="iconeclass" id="iconeclass" /></div></div>');
                            tree.edit($node);
                        }
                    },
                    "Supprimer": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Remove",
                        "action": function(obj) {
                            if ($node.type == "menu") {
                                var params = {};
                                params.idMENU = $node.id;
                                get_ajax_data("/gestion/asyn_menu_remove", params, "refresh_all_tree");

                            } else {
                                var params = {};
                                params.idmodul = $node.id;
                                get_ajax_data("/gestion/asyn_modif_modul", params, "refresh_all_tree");

                            }

                        }

                    }
                };
            }
        },
        'plugins': ["contextmenu", "dnd", "crrm", "types"]
    }).bind("copy_node.jstree", function(e, data) {
        if (data.original.type == "module") {
            var params = {};
            params.idmodul = data.original.id;
            params.idmenu = data.parent
            get_ajax_data("/gestion/asyn_modif_menu", params, "refresh_all_tree");
        } else {
            var params = {};
            params.idmodul = data.original.id;
            params.idmenu = data.parent
            get_ajax_data("/gestion/asyn_modif_menu_menu", params, "refresh_all_tree");
        }

    }).bind("move_node.jstree", function(e, data) {
        if (data.node.type == "module") {
            var params = {};
            params.idmodul = data.node.id;
            params.idmenu = data.parent
            get_ajax_data("/gestion/asyn_modif_menu", params, "refresh_all_tree");
        } else {
            var params = {};
            params.idmodul = data.node.id;
            params.idmenu = data.parent
            get_ajax_data("/gestion/asyn_modif_menu_menu", params, "refresh_all_tree");
        }
        set_menu_seq();
    });

}

function set_menu_seq() {

    var jsons = jQuery('#gst_modul_menu').jstree("get_json");
    get_ajax_data("/gestion/asyn_modif_menu_position", jsons, "");

}

function refresh_all_tree() {
    get_menu_list();
    get_vu_list();

}


/************************************************************* gestion droit acces ***************************************************************/
function ajout_droit() {
    idgrbdroit = $("#grbdroit").val();
    if (idgrbdroit != null) {
        var param = {};
        param.droitnom = $("#DROITnom").val();
        param.idgrbdroit = $("#grbdroit").val();
        get_ajax_data("/gestion/asyn_aj_droit", param, "aj_droit_callback");
    } else {
        $.alertMsg("Veuillez selectioné un groupe", "Message")
    }
}

function sup_droit() {

    $.confirm(
            "voulez vous supprimer le droit selectioné ?",
            function() {
                var param = {};
                param.iddroit = $("#droit").val();
                get_ajax_data("/gestion/asyn_sup_droit", param, "get_droit_list");
            })

}



function aj_droit_callback(data) {
    if (data["etat"] == 1) {
        $("#dg_aj_droit").dialog("close");
        get_droit_list();
    }
}


function get_droit_list() {
    idgrbdroit = $("#grbdroit").val();
    if (idgrbdroit != null) {
        var param = {};
        param.idgrbdroit = $("#grbdroit").val();
        get_ajax_data("/gestion/asyn_get_droit_list", param, "populate_droit_list");
    } else {

    }

}

function populate_droit_list(data) {
    var prflis = data["data"];
    $("#droit").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#droit").append("<option value='" + data["idDROIT"] + "'>" + data["DROITnom"] + "</option>");
    })
    $("#droit").select2("val", $("#droit").val())
    get_moduls();
}

function ajout_grbdroit() {
    var param = {};
    param.grbdroitnom = $("#GRBDROITnom").val();
    get_ajax_data("/gestion/asyn_aj_grbdroit", param, "aj_grbdroit_callback");
}

function aj_grbdroit_callback(data) {
    if (data["etat"] == 1) {
        $("#dg_aj_grbdroit").dialog("close");
        get_grbdroit_list();
    }
}

function sup_grbdroit() {
  
    $.confirm(
            "voulez vous supprimer le groupe de droits selectioné ?",
            function() {
                var param = {};
                param.idgrbdroit = $("#grbdroit").val();
                get_ajax_data("/gestion/asyn_sup_grbdroit", param, "get_grbdroit_list");
            })

}

function get_grbdroit_list() {
    get_ajax_data("/gestion/asyn_get_grbdoit_list", null, "populate_grbdroit_list");
}

function populate_grbdroit_list(data) {
    var prflis = data["data"];
    $("#grbdroit").html("");
    var html = ""

    $.each(prflis, function(index, data) {
        $("#grbdroit").append("<option value='" + data["idGRBDROIT"] + "'>" + data["GRBDROITnom"] + "</option>");
    })
    $("#grbdroit").select2("val", $("#grbdroit").val())
    get_droit_list();
}

function get_moduls() {
    var iddroit = $("#droit").val();
    if (iddroit != null) {
        var param = {}
        param.iddroit = $("#droit").val();
        get_ajax_data("/gestion/asyn_get_modul_list", param, "create_moduls_arborescence");
    } else {
        $("#gst_actions").jstree('destroy');
        $("#gst_actions").html('');
    }
}

function create_moduls_arborescence(data) {
    var action = data["data"];
    $("#gst_actions").jstree('destroy');
    $("#gst_actions").jstree({
        'core': {
            'data': action,
            "check_callback": true
        },
        'types': {
            'path': {
                'icon': "glyphicon glyphicon-plus",
                'max_depth': 3,
                "check_node": false,
                "uncheck_node": false
            },
            'vu': {
                'icon': "glyphicon glyphicon-list-alt"
            },
            'action': {
                'icon': "glyphicon glyphicon-cog"
            }

        },
        "contextmenu": {
            "items": function($node) {
                var tree = $("#gst_actions").jstree(true);
                return {
                    "Renomer": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Rename",
                        "action": function(obj) {
                            tree.edit($node);
                        }
                    }, "Categoriser": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Categoriser",
                        "action": function(obj) {



                            var html = '<div class="col-lg-9 col-lg-offset-2">'
                            html += '<div class="input-group input-group-sm top1">'
                            html += '<span class="input-group-addon">Catégorie :</span> '
                            html += '<input type="hidden" id="modulecatid" name="modulecatid" value="' + $node.id + '">'
                            html += '<select type="text" class="form-control" name="modulecat" id="modulecat" >'
                            html += '<option value="C">Création (ajout)</option>'
                            html += '<option value="R">Lecture (Consultation)</option>'
                            html += '<option value="U">Modification (Mise à jour)</option>'
                            html += '<option value="D">Suppression (Annulation)</option>'
                            html += '<option value="P">Paramétrage</option>'
                            html += '</select>'
                            html += '</div></div>'

                            var diag = $("<div></div>").dialog({
                                resizable: false,
                                height: 200,
                                width: 400,
                                buttons: {
                                    "Ok": function() {
                                        var param = {};
                                        param.catmodule = $("#modulecat").livequery(function() {
                                        }).val();
                                        param.idmodul = $("#modulecatid").livequery(function() {
                                        }).val();
                                        get_ajax_data("/gestion/asyn_module_cat", param, "cat_modul_callback");
                                        $(this).remove();
                                    }
                                },
                                close: function(event, ui) {
                                    $(this).remove();
                                },
                                resizable: false,
                                        title: "Categorisation d'une action",
                                modal: true
                            }).html(html);

                            diag.dialog("open")

                        }
                    }
                };
            }
        },
        "checkbox": {
            "two_state": true,
            "override_ui": true
        }, 'grid': {
            'columns': [{
                    'width': 250,
                    'header': "Nom de l'action"
                }, {
                    'width': 75,
                    'header': 'Catégorie',
                    'value': function(node) {
                        return (node.cat);
                    }
                }]
        },
        'plugins': ["contextmenu", "types", "checkbox", "grid", "ui", "sort"]
    }).bind("select_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#droit").val() != null && data.node.type != "path") {
            var params = {};
            params.iddroit = $("#droit").val();
            params.idmodul = data.node.id;
            params.type = "select";
            get_ajax_data("/gestion/asyn_droit_moduls", params, "");
        } else {

        }
        
    }).bind("deselect_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#droit").val() != null && data.node.type != "path") {
            var params = {};
            params.iddroit = $("#droit").val();
            params.idmodul = data.node.id;
            params.type = "unselect";
            get_ajax_data("/gestion/asyn_droit_moduls", params, "");
        } else {

        }
    }).bind("rename_node.jstree", function(event, data) {
        var params = {};
        params.modulnom = data.text;
        params.idmodul = data.node.id;
        get_ajax_data("/gestion/asyn_module_renome", params, "refresh_all_tree");
    })






}

function cat_modul_callback(){
    get_moduls();
}

/************************************* gestion droit des profils ********************************************/

function get_droits() {

    idprf = $("#prf").val();
    if (idprf != null) {
        var param = {}
        param.idprf = idprf;
        get_ajax_data("/gestion/asyn_get_all_droits", param, "create_droits_arborescence");
    } else {
        $("#gst_droit_profil").jstree('destroy');
        $("#gst_droit_profil").html("");
    }

}

function create_droits_arborescence(data) {
    var action = data["data"];
    $("#gst_droit_profil").jstree('destroy');
    $("#gst_droit_profil").jstree({
        'core': {
            'data': action,
            "check_callback": true
        },
        'types': {
            'grb': {
                'icon': "glyphicon glyphicon-plus",
                'max_depth': 3,
                "check_node": false,
                "uncheck_node": false
            },
            'droit': {
                'icon': "glyphicon glyphicon-thumbs-up"
            }

        },
        "contextmenu": {
            "items": function($node) {
                var tree = $("#gst_droit_profil").jstree(true);
                return {
                    "Rename": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Rename",
                        "action": function(obj) {
                            tree.edit($node);
                        }
                    }
                };
            }
        },
        "checkbox": {
            "two_state": true,
            "override_ui": true
        },
        'plugins': ["contextmenu", "types", "checkbox", "ui", "sort"]
    }).bind("select_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;
       
        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#prf").val() != null && data.node.type != "grb") {
            var params = {};
            params.iddroit = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "select";
            get_ajax_data("/gestion/asyn_prf_droit", params, "");
        } else {

        }
    }).bind("deselect_node.jstree", function(e, data) {


        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#prf").val() != null && data.node.type != "grb") {
            var params = {};
            params.iddroit = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "unselect";
            get_ajax_data("/gestion/asyn_prf_droit", params, "");
        } else {

        }
    })

}


/****************************************** gestion magasin pour profils ************************************************/

function get_mag() {
    idprf = $("#prf").val();
    if (idprf != null) {
        var param = {}
        param.idprf = idprf;
        get_ajax_data("/gestion/asyn_get_all_mag", param, "create_mag_arborescence");
    } else {
        $("#gst_mag_profil").jstree('destroy');
        $("#gst_mag_profil").html("");
    }
}

function create_mag_arborescence(data) {

    var action = data["data"];
    $("#gst_mag_profil").jstree('destroy');
    $("#gst_mag_profil").jstree({
        'core': {
            'data': action,
            "check_callback": true
        },
        'types': {
            'mag': {
                'icon': "glyphicon glyphicon-home",
                "check_node": false,
                "uncheck_node": false
            }

        },
        "checkbox": {
            "two_state": true,
            "override_ui": true
        },
        'plugins': ["types", "checkbox", "ui"]
    }).bind("select_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#prf").val() != null) {
            var params = {};
            params.idmag = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "select";
            get_ajax_data("/gestion/asyn_mag_prf", params, "");
        } else {

        }

    }).bind("deselect_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#prf").val() != null) {
            var params = {};
            params.idmag = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "unselect";
            get_ajax_data("/gestion/asyn_mag_prf", params, "");

        } else {

        }
    })


}

/****************************************** gestion exercice pour profils ************************************************/

function get_exe() {
    idprf = $("#prf").val();
    if (idprf != null) {
        var param = {}
        param.idprf = idprf;
        get_ajax_data("/gestion/asyn_get_all_exe", param, "create_exe_arborescence");
    } else {
        $("#gst_exe_profil").jstree('destroy');
        $("#gst_exe_profil").html("");
    }
}

function create_exe_arborescence(data) {

    var action = data["data"];
    $("#gst_exe_profil").jstree('destroy');
    $("#gst_exe_profil").jstree({
        'core': {
            'data': action,
            "check_callback": true
        },
        'types': {
            'exe': {
                'icon': "glyphicon glyphicon-calendar",
                "check_node": false,
                "uncheck_node": false
            },
            'exec': {
                'icon': "glyphicon glyphicon-calendar",
                'select_node': false
            }

        },
        "checkbox": {
            "two_state": true,
            "override_ui": true
        },
        'plugins': ["types", "checkbox", "ui", "sort"]
    }).bind("select_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }
        
        if ($("#prf").val() != null) {
            var params = {};
            params.idexe = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "select";
            get_ajax_data("/gestion/asyn_exe_prf", params, "");
        } else {

        }

    }).bind("deselect_node.jstree", function(e, data) {

        var e = window.event || event;
        var button = e.which || e.button;

        if (button != 1 && (typeof button != "undefined")){
            data.instance.deselect_node(data.node);
            return false;
        }

        if ($("#prf").val() != null) {
            var params = {};
            params.idexe = data.node.id;
            params.idprf = $("#prf").val();
            params.type = "unselect";
            get_ajax_data("/gestion/asyn_exe_prf", params, "");

        } else {

        }
    })
}





