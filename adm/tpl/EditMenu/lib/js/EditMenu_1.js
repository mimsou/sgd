$(document).ready(function() {

    // bouton de groupes
    $(".groupe button:first-child").livequery(function() {
        $(this).click(function() {
            var param = {};
            var id_groupe = $(this).parent().attr("id");
            var id_acg = $("#acg-list option:selected").val();
            param.id_groupe = id_groupe;
            param.id_acg = id_acg;
            get_ajax_data("/EditMenu/ajax_get_groupe_params", param, "populate_groupe_edtion");
        })
    })

    $(".delete-module").livequery(function() {
        $(this).click(function() {
            var param = {};
            var id_module = $(this).parent().attr("id");
            var id_acg = $("#acg-list option:selected").val();
            var id_groupe = $(".edition-groupe").attr("id");
            param.id_module = id_module;
            param.id_acg = id_acg;
            param.id_groupe = id_groupe;
            get_ajax_data("/EditMenu/ajax_delete_menu_affectation", param, "delete_module");
        })
    })

    $(".list-item_module").livequery(function() {
        $(this).draggable({
            appendTo: "body",
            helper: "clone"
        });
    })

    $("#content_groupe").livequery(function() {
        $(this).droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ":not(.ui-sortable-helper)",
            drop: function(event, ui) {
                var id_module = ui.draggable.attr("id");
                var exist = false;
                $("#content_groupe").children(".list_content").each(function() {
                    if ($(this).attr("id") == id_module) {
                        exist = true;
                    }
                })
                if (!exist) {
                    if (typeof $(".edition-groupe").attr("id") != 'undefined') {
                        var param = {};
                        var id_acg = $("#acg-list option:selected").val();
                        var id_groupe = $(".edition-groupe").attr("id");
                        param.id_module = id_module;
                        param.id_acg = id_acg;
                        param.id_groupe = id_groupe;
                        get_ajax_data("/EditMenu/ajax_menu_affect", param, "ajax_menu_affect");
                        $("<li id='" + id_module + "' class='list-group-item list_content'></li>").html("<span>" + (ui.draggable.children("span").text()) + "</span><button  class='delete-module btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-remove'></span></button>").appendTo(this);
                    }
                }
            }
        })
    });

    $(".list-group-item").livequery(function() {
        $(this).children("#supp-item-list").click(function() {
            var param = {};
            param.id = $(this).parent().attr("id")
            get_ajax_data("/EditMenu/ajax_delete_item_access_list", param, "delete_item_access_liste");
        })
    })

    // configurer le dialogue de création de groupe
    $("#acgdiag").dialog({
        resizable: false,
        height: 300,
        width: 700,
        modal: false,
        autoOpen: false,
        buttons: {
            "Créer un groupe": function() {
                get_ajax_data("/EditMenu/ajax_create_access_groupe_item", null, "add_access_groupe_item");
            },
            "quitter": function() {
                get_ajax_data("/EditMenu/ajax_get_access_groupe", null, "populate_access_groupe_selection");
                $(this).dialog("close");
            }
        }
    });


    // bouton edition création groupe
    $("#access_edit").click(function() {
        get_ajax_data("/EditMenu/ajax_get_access_groupe", null, "populate_access_groupe_edtion");
        $("#acgdiag").dialog("open");
    })


    $("#espace").change(function() {
        get_modules_and_groupes();
    })

    $("#acg-list").change(function() {
        get_modules_and_groupes();
    })

    $("#status_groupe").livequery(function() {
        $(this).click(function() {
            var param = {};
            var id_module = $(this).parent().attr("id");
            var id_acg = $("#acg-list option:selected").val();
            var etas = $(this).parent().attr("rel");
            param.id_module = id_module;
            param.id_acg = id_acg;
            param.etas = etas;
            get_ajax_data("/EditMenu/ajax_edit_access", param, "status_groupe_toggle");
        })
    })

    $(".list-item_module").livequery(function() {
        $(this).children("#status_module").click(function() {
            var param = {};
            var id_module = $(this).parent().attr("id");
            var id_acg = $("#acg-list option:selected").val();
            var etas = $(this).parent().attr("rel");
            param.id_module = id_module;
            param.id_acg = id_acg;
            param.etas = etas;
            get_ajax_data("/EditMenu/ajax_edit_access", param, "status_module");
        })
    })

    get_ajax_data("/EditMenu/ajax_get_access_groupe", null, "populate_access_groupe_selection");

    $(".editTaskBigG").livequery(function() {
        $(this).click(function() {
            $(this).replaceWith('<input type="text" class="editInputTaskBigG" value="' + $(this).text() + '" />');
        })
    })

    $(".editInputTaskBigG").livequery(function() {
        $(this).focusout(function() {
            if ($(this).val() != "") {
                var param = {};
                var id_groupe = $(this).parent().attr("id");
                var name = $(this).val();
                param.id_groupe = id_groupe;
                param.name = name;
                get_ajax_data("/EditMenu/ajax_change_groupe_name", param, "update_module_name");
                $(this).replaceWith("<span id='titre_groupe'  class='editTaskBigG' >" + $(this).val() + "</span>");
            }
        })
    })



    $(".editTaskBigMd").livequery(function() {
        $(this).click(function() {
            $(this).replaceWith('<input style="width:100px;" type="text" class="editInputTaskBigMd" value="' + $(this).text() + '" />');
        })
    })

    $(".editInputTaskBigMd").livequery(function() {
        $(this).focusout(function() {
            if ($(this).val() != "") {
                var param = {};
                var id_module = $(this).parent().attr("id");
                var name = $(this).val();
                param.id_module = id_module;
                param.name = name;
                get_ajax_data("/EditMenu/ajax_change_module_name", param, "update_module_name");
                $(this).replaceWith("<span id='titre_module'  class='editTaskBigMd' >" + $(this).val() + "</span>");
            }
        })
    })
})

function ajax_menu_affect(data) {

}

function delete_module(data) {
    if (data.status == "1") {

        $("#content_groupe").livequery(function() {
            $(this).children("#" + data.id_module).remove();
        })
    }

}

function status_module(data) {
    get_modules_and_groupes();
}


function status_groupe_toggle(data) {

    var status = $("#status_groupe");
    if (status.parent().attr("rel") == "a") {
        status.parent().attr("rel", "d");
        status.removeClass().addClass("btn btn-default pull-right btn-xs btn-danger").html("Desactivé");
    } else {
        status.parent().attr("rel", "a");
        status.removeClass().addClass("btn btn-default pull-right btn-xs btn-success").html("Actif");
    }

}



function get_modules_and_groupes() {
    var params = {};
    var mode = $("#espace option:selected").val();
    var id_acg = $("#acg-list option:selected").val();
    params.mode = mode;
    params.id_acg = id_acg;

    get_ajax_data("/EditMenu/ajax_get_modules", params, "populate_modules");
    get_ajax_data("/EditMenu/ajax_get_groupes", params, "populate_groupes");

    $("#titre_groupe").html("");
    $("#status_groupe").hide();
    $("#content_groupe").html("");

}

function populate_modules(data) {
    var modules = data;
    var html = "";
    $.each(modules, function(index, value) {
        if (value.access == "0") {
            var clas = "btn btn-default pull-right btn-xs btn-danger";
            var text = "Désactivé";
            var rel = "d";
        } else {
            var clas = "btn btn-default pull-right btn-xs btn-success";
            var text = "Actif";
            var rel = "a";
        }
        html += '<li rel="' + rel + '" id="' + value.id + '" class="list-group-item list-item_module small"><span class="editTaskBigMd">' + value.front_name + ' </span><button id="status_module" class="' + clas + '" type="button">' + text + '</button></li>';
    })
    $("#list_module").html(html);

}

function populate_groupes(data) {

    var groupes = data;
    var html = "";
    $.each(groupes, function(index, value) {
        html += '<div class="groupe" id="' + value.id + '"><button id="cliquable" type="button" class="btn btn-default btn-xs">' + value.front_name + '</button> </div>';
    })
    $("#liste_groupe").html(html);
    $("#num_groupe").html(groupes.length);

}

function delete_item_access_liste(data) {

    var responce = data[0];
    var id_acg = data[1];
    if (responce == "1") {
        $("#list_access_groupe").livequery(function() {
            $(this).children("#" + id_acg).hide();
        })

        $("#acg-list").livequery(function() {
            $(this).children("#" + id_acg).hide();
        })
    }
}

function populate_access_groupe_selection(data) {
    var accgroupe = data;
    var html = "";
    $.each(accgroupe, function(index, value) {
        html += '<option id="' + value.id_acg + '" value ="' + value.id_acg + '">' + value.nom + '</option>';
    })
    $("#acg-list").html(html);
    get_modules_and_groupes();
}

//ajouté une nouveau groupe d'access
function add_access_groupe_item(data) {
    $("#list_access_groupe").append("<li class='list-group-item' id='" + data + "'>Nouveau groupe d'access<button type='button class='btn btn-default pull-right btn-xs btn-success pull-right' id='supp-item-list'>supprimer</button></li>");
}

//recupéré les information du groupe selectionée
function populate_groupe_edtion(data) {

    //recupérer les element du DOM
    var status = $("#status_groupe");
    var titre = $("#titre_groupe");

    //nomenclature des données
    var groupe = data[0][0];
    var modules = data[1];

    $("#content_groupe").html("");
    $.each(modules, function(index, value) {
        $("<li id='" + value.id_module_m + "' class='list-group-item list_content'></li>").html("<span>" + (value.front_name) + "</span><button  class='delete-module btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-remove'></span></button>").appendTo("#content_groupe");
    })


    //changer le status
    if (groupe.access == 1) {
        status.parent().attr("rel", "a");
        status.removeClass().addClass("btn btn-default pull-right btn-xs btn-success").html("Actif");
    } else {
        status.parent().attr("rel", "d");
        status.removeClass().addClass("btn btn-default pull-right btn-xs btn-danger").html("Desactivé");
    }
    titre.html(groupe.front_name);
    status.parent().attr("id", groupe.id);
    status.show();
}

function populate_access_groupe_edtion(data) {
    var accgroupe = data;
    var html = '<ul class="list-group" id="list_access_groupe">';
    $.each(accgroupe, function(index, value) {
        html += '<li class="list-group-item" id="' + value.id_acg + '">' + value.nom + '<button type="button" class="btn btn-default pull-right btn-xs sise_1 btn-danger pull-right" id="supp-item-list">supprimer</button></li>';
    })
    html += '</ul>'
    $("#access_groupe_container").html(html);
}
