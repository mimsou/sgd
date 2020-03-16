$(document).ready(function() {


    $(".tablesorter").livequery(function() {
        $(this).tablesorter({
            theme: "bootstrap",
            widthFixed: false,
            headerTemplate: "{content} {icon}",
            widgets: ["uitheme", "zebra"],
            widgetOptions: {
                zebra: ["even", "odd"],
                filter_reset: ".reset"
            }
        })
    });


    $.contextMenu({
        selector: "table",
        callback: function(key, options) {
            tableToExcel("#" + $(this).attr("id"), "Table Excel");
        },
        items: {
            "Exporter Excel": {name: "Exporter Excel", icon: "edit"},
        }
    });

    $("#btn_imp").click(function() {
        if ($("#data_tat").children().length > 0) {
            var elma = $("#printarea").clone();
            elma.find(".noprint").remove();
            var elmb = $("<div></div>").append(elma);
            elmb.printThis({
                importCSS: true,
                removeInline: false,
                pageTitle: "Liste"
            });
        } else {
            $.alertMsg("La liste à imprimer est vide", "Impression");
        }
    })


    $("#SOCIETE").attr("readonly", "");
    $("#SOCIETE").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    })


    $("#btn_gen").click(function() {
        get_etat();
    })


    $("#filtre_etat").validate({
        rules: {
        }
    })

    $("#etat").change(function() {
        get_etat_config()
    })


})

function get_etat_config() {
    var params = {};
    params.idETAT = $("#etat").val();
    get_ajax_data("/etatgensys/asyn_get_config_etat", params, "get_etat_config_callback");

}

function get_etat_config_callback(data) {

    var param = data["data"];
    var datas = param.data
    var sgbd = param.sgbd
    var html = "";
    var thhtml = "";

    $.each(datas, function(index, elm) {

        $.each(elm.champs, function(index, elm) {

            if (sgbd == "db2") {


                if (elm.desc.length != 0) {
                    var descchamp = elm.desc;
                } else {
                    var descchamp = elm.discription;
                }

                if (elm.afficher == "true") {
                    thhtml += '<th>' + descchamp + '</th>';
                }

                if (elm.search == "true") {
                    html += '<div class="col-md-3">';
                    html += '<div class="input-group input-group-sm">';
                    html += '<span class="input-group-addon">' + descchamp + ': </span>';
                    html += '<input type="text" class="form-control" id="' + elm.name + 'R" name="' + elm.name + 'R" />';
                    html += '</div>';
                    html += '</div>';
                }

            } else if (sgbd == "mysql") {


                if (elm.desc.length != 0) {
                    var descchamp = elm.desc;
                } else {
                    var descchamp = elm.name;
                }

                if (elm.afficher == "true") {
                    thhtml += '<th>' + descchamp + '</th>';
                }

                if (elm.search == "true") {
                    html += '<div class="col-md-3">';
                    html += '<div class="input-group input-group-sm">';
                    html += '<span class="input-group-addon">' + descchamp + ': </span>';
                    html += '<input type="text" class="form-control" id="' + elm.name + 'R" name="' + elm.name + 'R" />';
                    html += '</div>';
                    html += '</div>';
                }

            }
        })
    })

    $("#ETAchamp").html(thhtml);

    $("#ETAsearch").html(html);


    $.each(datas, function(index, elm) {
        $.each(elm.champs, function(index, elm) {
            if (elm.date == true) {
                $("#".elm.name).attr("readonly", "");
                $("#".elm.name).datepicker({
                    dateFormat: 'dd/mm/yy',
                    changeMonth: true,
                    changeYear: true
                })
            }
        })
    })

}

function get_etat() {

    var params = getFromObj("#filtre_etat");
    get_ajax_data("/etatgensys/asyn_get_etat", params, "get_etat_pupulate");

}
function get_etat_pupulate(data) {

    var datas = data["data"]

    var html = "";

    $.each(datas, function(index, elm) {
         html += '<tr>'
        $.each(elm, function(index, elms) {  
            html += '<td>' + elm[index] + '</td>'
        })
        html += '</tr>'
    })


    $("#ETAdata").html(html);


}
