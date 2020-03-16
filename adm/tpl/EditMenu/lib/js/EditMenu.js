$(document).ready(function() {

    //initialisation groupe d'acces
    init_access_groupe();

    //initialisation des actions
    init_actions();

});


/////////////Groupe d'access///////////////
function init_access_groupe() {

    get_ajax_data("/EditMenu/ajax_get_access_groupe", null, "populate_access_groupe_selection");

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

    $("#access_edit").click(function() {
        get_ajax_data("/EditMenu/ajax_get_access_groupe", null, "populate_access_groupe_edtion");
        $("#acgdiag").dialog("open");
    })

    $(".list-group-item").livequery(function() {
        $(this).children("#supp-item-list").click(function() {
            var param = {};
            param.id = $(this).parent().attr("id")
            get_ajax_data("/EditMenu/ajax_delete_item_access_list", param, "delete_item_access_liste");
        })
    })

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

function populate_access_groupe_selection(data) {
    var accgroupe = data;
    var html = "";
    $.each(accgroupe, function(index, value) {
        html += '<option id="' + value.id_acg + '" value ="' + value.id_acg + '">' + value.nom + '</option>';
    })
    $("#acg-list").html(html);
    
}

function add_access_groupe_item(data) {
    $("#list_access_groupe").append("<li class='list-group-item' id='" + data + "'>Nouveau groupe d'access<button type='button class='btn btn-default pull-right btn-xs btn-success pull-right' id='supp-item-list'>supprimer</button></li>");
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
///////////////////////////////////



/////////////Actions///////////////

function init_actions() {
    var mode = $("#espace option:selected").val();
    var id_acg = $("#acg-list option:selected").val();

    var param = {};
    param.id_acg = id_acg;
    param.mode = mode;
    param.ajax = $.btn_ischeck(".ajax");
    param.vu = $.btn_ischeck(".vu");
    get_ajax_data("/EditMenu/ajax_get_modules", param, "pupulate_modules");
}


function pupulate_modules(data){
    
}