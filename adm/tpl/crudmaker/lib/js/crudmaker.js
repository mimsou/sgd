var elmfk;
$(document).ready(function() {

    $("#filtre_crud").validate({
        rules: {
            BASE: {
                required: true
            }, TABLE: {
                required: true
            }, con: {
                required: true
            }
        }
    })

    $("#con").blur(function() {
        var param = {};
        if ($("#con").val().length > 0) {
            param.con = $("#con").val();
            get_ajax_data("/crudmaker/asyn_get_base_mysql", param, "populate_base");
        }

    })

    $("#BASE").change(function() {
        var param = {};
        if ($("#BASE").val().length > 0) {
            param.database = $("#BASE").val();
            if ($("#con").val().length > 0) {
                param.con = $("#con").val();
                get_ajax_data("/crudmaker/asyn_get_table_mysql", param, "populate_table");
            }
        }

    })

    $("#basefk").change(function() {
        var param = {};
        if ($("#basefk").val().length > 0) {
            param.database = $("#basefk").val();
            if ($("#con").val().length > 0) {
                param.con = $("#con").val();
                get_ajax_data("/crudmaker/asyn_get_table_mysql", param, "populate_table_fk");
            }
        }
    })

    $("#tablefk").change(function() {
        var param = {};
        if ($("#basefk").val().length > 0) {
            param.database = $("#basefk").val();
            if ($("#con").val().length > 0) {
                param.con = $("#con").val();
                param.table = $("#tablefk").val();
                get_ajax_data("/crudmaker/asyn_get_table_column_mysql", param, "populate_table_culumn_fk");
            }
        }
    })


    $("#btn_compiler").click(function() {
        $("<div></div>").dialog({
            resizable: false,
            height: 170,
            width: 375,
            buttons: {
                "Ok": function() {
                    var name = $("#module").livequery(function() {
                    }).val()
                    if (name.length > 0) {
                        compiler(name);
                        $(this).dialog("close");
                    }
                }
            },
            close: function(event, ui) {
                $(this).remove();
            },
            resizable: false,
                    title: "création du composant",
            modal: true
        }).html('<div class="col-lg-9 col-lg-offset-2"><div class="input-group input-group-sm top1"><span class="input-group-addon">Nom du composant :</span> <input type="text" class="form-control" name="module" id="module" /></div></div>');

    })


    $("#fkmake").dialog({
        resizable: false,
        height: 400,
        width: 375,
        autoOpen: false,
        buttons: {
            "Ok": function() {
                if ($("#tablefk").val().length > 0 && $("#keyfk").val().length > 0 && $("#libfk").val().length > 0) {
                    var parfk = "{'base':'" + $("#basefk").val() + "',"
                    parfk += "'table':'" + $("#tablefk").val() + "',"
                    parfk += "'key':'" + $("#keyfk").val() + "',"
                    parfk += "'lib':'" + $("#libfk").val() + "'}"

                    elmfk.val(parfk);
                    $("#fkmake").dialog("close");
                }
            }
        },
        close: function(event, ui) {
            if ($("#tablefk").val().length > 0 && $("#keyfk").val().length > 0 && $("#libfk").val().length > 0) {

            } else {
                elmfk.parent().find("#fkstate").attr("checked", false);
            }

            $("#fkmake").dialog("close");
        },
        resizable: false,
                modal: true
    })


    $(".fkstate").livequery(function() {
        $(this).change(function() {
            if ($(this).is(":checked")) {
                $("#forekey").html($(this).parent().find("#itemname").val() + "<=>");
                elmfk = $(this).parent().find("#fkdata");
                $("#fkmake").dialog("open");
            } else {
                $(this).parent().find("#fkdata").val("");
            }
        })
    })

})


function populate_base(data) {

    $("#BASE").html("");
    $("#basefk").html("");

    $.each(data["database"], function(index, elm) {
        $("#BASE").append('<option value=' + elm.Database + '>' + elm.Database + '</option>')
        $("#basefk").append('<option value=' + elm.Database + '>' + elm.Database + '</option>')
    })

}

function populate_table(data) {

    $("#TABLE").html("");

    $.each(data["table"], function(index, elm) {
        $("#TABLE").append('<option value=' + elm.table_name + '>' + elm.table_name + '</option>')

    })

}

function populate_table_fk(data) {


    $("#tablefk").html("");

    $.each(data["table"], function(index, elm) {
        $("#tablefk").append('<option value=' + elm.table_name + '>' + elm.table_name + '</option>')
    })

}

function  populate_table_culumn_fk(data) {


    $("#keyfk").html("");
    $("#libfk").html("");

    $.each(data["column"], function(index, elm) {
        $("#keyfk").append('<option value=' + elm.Field + '>' + elm.Field + '</option>')
        $("#libfk").append('<option value=' + elm.Field + '>' + elm.Field + '</option>')
    })

}


function get_shema() {

    var param = {}
    param.base = $("#BASE").val();
    param.table = $("#TABLE").val();
    param.sgbd = $("#sgbd").val();
    param.con = $("#con").val();

    if ($("#sgbd").val() == "mysql") {
        get_ajax_data("/crudmaker/asyn_get_shema_mysql", param, "get_shema_pupulate");
    } else if ($("#sgbd").val() == "db2") {
        get_ajax_data("/crudmaker/asyn_get_shema_db2", param, "get_shema_pupulate");
    }

}

function get_shema_pupulate(data) {

    if (data["etat"] == "0") {

        var datas = data["data"];
        var html = ' <div class="col-md-12" id="globcontain">'
        html += '<div class="panel panel-default">'
        html += '<div class="panel-heading"><div class="col-md-1">' + data["table"] + ' : </div>  <div class="col-md-4"><div class="input-group-sm"><input class="form-control input-sm" id="intername"></div></div><button onClick="javascript:$(this).parents(' + "'" + "#globcontain" + "'" + ').remove();" class="btn btn-danger btn-xs pull-right">Fermer</button><div class="clearfix"></div></div>'
        html += '<div class="panel-body tb"  >'
        html += '<input type="hidden" value="' + data["base"] + '" id="basename">'
        html += '<input type="hidden" value="' + data["table"] + '" id="tablename">'

        $.each(datas, function(index, elm) {

            html += '<div class="input-group input-group-sm">'
            html += '<span style="background-color: #fff" class="input-group-addon"><div style="width: 200px;text-align: left;"><strong>' + elm.NAME + '</strong> : ' + elm.DESCRIPTION + ' </div></span>'

            html += '<span class="input-group-addon tbitem">'
            html += '<input type="hidden" value="' + elm.NAME + '" id="itemname" >'
            html += '<input type="hidden" value="' + elm.TYPE + '" id="itemnat" >'
            html += '<input type="hidden" value="' + elm.DESCRIPTION + '" id="description" >'
            if (elm.INDEX == 1) {
                html += '<span style="color:red">Index&nbsp;&nbsp;</span><input type="hidden" value="true" id="index" name="index" />';
            } else {
                html += '<input type="hidden" value="false" id="index" name="index" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            }
            html += '<div class="col-md-3">Nom de champ :  <input class="form-control" name="desc" id="desc" ></div>'
            html += 'Ai<input type="checkbox" id="ai" name="ai" />&nbsp;&nbsp;'
            html += 'Search<input type="checkbox" id="search" name="search" />&nbsp;&nbsp;'
            html += 'Date<input type="checkbox" id="date" name="date" />&nbsp;&nbsp;'
            html += 'Require<input type="checkbox" id="require" name="require" />&nbsp;&nbsp;'
            html += 'Afficher<input type="checkbox" checked="" id="afficher" name="afficher" />&nbsp;&nbsp;'
            html += 'Modifier<input type="checkbox" checked="" id="modifier" name="modifier" /> '
            html += 'fk<input type="checkbox" class="fkstate" id="fkstate" name="fkstate" /> '
            html += '<input type="hidden"  id="fkdata" name="fkdata" /> '
            html += '</span>'
            html += '</div>'
        })

        html += '</div>'
        html += '</div>'
        html += '</div>'

        $("#shemaarea").append(html);


        $("#sgbd").attr("disabled", "disabled");
        $("#con").attr("disabled", "disabled");

    }

}

function compiler(name) {
    var param = {};
    param.modname = name;
    param.con = $("#con").val();
    param.sgbd = $("#sgbd").val();
    param.data = [];
    $(".tb").each(function() {
        var item = {};
        item.base = $(this).find("#basename").val();
        item.table = $(this).find("#tablename").val();
        item.intername = $(this).parent().find("#intername").val();
        item.champs = [];
        $(this).find(".tbitem").each(function() {

            var itemtab = {};
            itemtab.name = $(this).find("#itemname").val();
            itemtab.type = $(this).find("#itemnat").val();
            itemtab.discription = $(this).find("#description").val();
            itemtab.desc = $(this).find("#desc").val();
            itemtab.index = $(this).find("#index").val();
            itemtab.search = $(this).find("#search").is(":checked");
            itemtab.ai = $(this).find("#ai").is(":checked");
            itemtab.date = $(this).find("#date").is(":checked");
            itemtab.required = $(this).find("#require").is(":checked");
            itemtab.afficher = $(this).find("#afficher").is(":checked");
            itemtab.modifier = $(this).find("#modifier").is(":checked");
            if ($(this).find("#fkdata").val().length != 0) {
                var str = $(this).find("#fkdata").val().replace(/'/g,'"');
                itemtab.fkparam = JSON.parse(str);
            } else {
                itemtab.fkparam = "";
            }

            item.champs.push(itemtab);
        })
        param.data.push(item);
    })

    get_ajax_data("/crudmaker/asyn_compile", param, false);

}




