var aliass;
var aliasss = "";
var countShema = 0;
var upmod = false;
var modeldata;
$(document).ready(function() {

    $("#filtre_etat").validate({
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

    $("#ALIAS").val("A");
    aliass = "A";

    $("#con").blur(function() {
        var param = {};
        if ($("#con").val().length > 0) {
            param.con = $("#con").val();
            get_ajax_data("/etatgensys/asyn_get_base_mysql", param, "populate_base");
        }

    })

    $("#BASE").change(function() {
        var param = {};
        if ($("#BASE").val().length > 0) {
            param.database = $("#BASE").val();
            if ($("#con").val().length > 0) {
                param.con = $("#con").val();
                get_ajax_data("/etatgensys/asyn_get_table_mysql", param, "populate_table");
            }
        }

    })

    $("#sgbd").select2();


    $('#tabs').tab();

    var pos = 0;
    var ht = $(window).height() - $("#areaobj").offset().top - 95

    $("#areaobj").height(ht);
    $("#wherexpr").height(ht);
    $("#selectexp").height(ht);
    $("#listobj").height(ht);

    $('#swipeLeft').hide();
    $('.expression').slideUp();
    $("#btn_compiler").hide();
    $("#btn_ajouter").show();

    $('#swipeRight').click(function() {

        if (pos + 1 < 1) {
            $('#swipeRight').show();
            $('#swipeLeft').show();
            pos++;
        } else {
            $('.tables').slideUp();
            $('#tableelm').slideUp();
            $('.expression').slideDown();
            $("#btn_compiler").show();
            $("#btn_ajouter").hide();
            $('#swipeLeft').show();
            $(this).hide();
        }
    });

    $('#swipeLeft').click(function() {
        $('.swipeBox').slideToggle("slow");
        if (pos - 1 > 0) {
            $('#swipeLeft').show();
            $('#swipeRight').show();
            pos--;
        } else {
            $('.tables').slideDown();
            $("#btn_compiler").hide();
            $("#btn_ajouter").show();
            $('.expression').slideUp();
            $('#tableelm').slideDown();
            $('#swipeRight').show();
            $(this).hide();
        }
    });

    $("#btn_compiler").click(function() {

        $("<div></div>").dialog({
            resizable: false,
            height: 170,
            width: 300,
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
                    title: "crée un module",
            modal: true
        }).html('<div class="col-lg-9 col-lg-offset-2"><div class="input-group input-group-sm top1"><span class="input-group-addon">Module :</span> <input type="text" class="form-control" name="module" id="module" /></div></div>');

    })

    $("#etat").change(function() {
        get_model_from_id($(this).val());
    })


    //connection

    var sourceAnchors = [
        [0, 1, 0, 1]
    ];

    window.instance = jsPlumb.getInstance({
        // set default anchors.  the 'connect' calls below will pick these up, and in fact setting these means
        // that you also do not need to supply anchor definitions to the makeSource or makeTarget functions. 
        Anchors: [sourceAnchors, "TopCenter"],
        // drag options
        DragOptions: {cursor: "pointer", zIndex: 2000},
        // default to blue at source and green at target
        EndpointStyles: [{fillStyle: "#0d78bc"}, {fillStyle: "#558822"}],
        // blue endpoints 7 px; green endpoints 11.
        Endpoints: [["Dot", {radius: 4}], ["Dot", {radius: 4}]],
        // default to a gradient stroke from blue to green.  for IE provide all green fallback.
        PaintStyle: {
            strokeStyle: "#000",
            lineWidth: 2
        },
        Container: "shemaarea"
    });


    var lastFocusedId;
    $(document).on('focus', '*', function() {
        var lastFocusedIds = $(this).attr("id");
        if (lastFocusedIds == "editselect" || lastFocusedIds == "editwher" || lastFocusedIds == "editgroup" || lastFocusedIds == "editorder") {
            lastFocusedId = lastFocusedIds;
        }
    });

    $(".btntable").livequery(function() {
        $(this).click(function() {

            if (lastFocusedId == "editselect" || lastFocusedId == "editwher" || lastFocusedId == "editgroup" || lastFocusedId == "editorder") {
                if (lastFocusedId == "editgroup" || lastFocusedId == "editorder") {
                    insertAtCaret(lastFocusedId, " " + $(this).attr("id") + " ,");
                    $("#" + lastFocusedId).focus();
                } else {
                    // $("#" + lastFocusedId).val($("#" + lastFocusedId).val() + $(this).attr("id"));
                    insertAtCaret(lastFocusedId, " " + $(this).attr("id") + " ");
                    $("#" + lastFocusedId).focus();
                }
            }
        })
    })

    $(".remexp").livequery(function() {
        $(this).click(function() {
            $(this).parents(".expcont").remove();
        })
    })

    $("#selectplus").click(function() {

        if ($("#editselect").val().length > 0 && $("#asselect").val().length > 0) {

            var html = '<div class="input-group input-group-sm expcont" style="margin-bottom:5px;" >';
            html += '<input type="text" disabled="" class="form-control selectent" al="' + $("#asselect").val() + '" value="(' + $("#editselect").val() + ') ' + $("#asselect").val() + '">';
            html += '<span class="input-group-btn">';
            html += '<button class="btn btn-danger remexp" id="whereplus"><span class="glyphicon glyphicon-remove-sign"></span></button>';
            html += '</span>';
            html += '</div>';

            $("#editselect").val("");
            $("#asselect").val("")
            $("#selectrender").append(html);
        } else {
            $.alertMsg("Merci de complété l'expression");
        }

    });

    $("#whereplus").click(function() {

        if ($("#editwher").val().length > 0) {

            var html = '<div class="input-group input-group-sm expcont" style="margin-bottom:5px;">';
            html += '<input type="text" disabled="" class="form-control whereent" value="(' + $("#editwher").val() + ')">';
            html += '<span class="input-group-btn">';
            html += '<button class="btn btn-danger remexp" ><span class="glyphicon glyphicon-remove-sign"></span></button>';
            html += '</span>';
            html += '</div>';

            $("#editwher").val("");
            $("#aswher").val("");
            $("#whererender").append(html);

        } else {
            $.alertMsg("Merci de complété l'expression");
        }

    });


})

function insertAtCaret(areaId, text) {
    var caretPos = document.getElementById(areaId).selectionStart;
    var textAreaTxt = jQuery("#" + areaId).val();
    var txtToAdd = text;
    jQuery("#" + areaId).val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
}


function get_connect() {
    


    // bind to a connection event, just for the purposes of pointing out that it can be done.
    instance.bind("connection", function(i, c) {

    });

    instance.bind("connectionDetached", function(i, c) {

    });

    var out = window.out = jsPlumb.getSelector(".connect-out");
    var inn = window.inn = jsPlumb.getSelector(".connect-in");

    // get the list of ".smallWindow" elements.            


    // suspend drawing and initialise.
    instance.doWhileSuspended(function() {


        instance.makeSource(out, {
            anchor: "RightMiddle",
            isSource: true,
            maxConnections: 1
        });

        // configure the .smallWindows as targets.
        instance.makeTarget(inn, {
            anchor: "LeftMiddle",
            dropOptions: {hoverClass: "hover"},
            uniqueEndpoint: true
        });


    });
    
    jsPlumb.fire("etatgen", instance);
}

function get_model_from_id(id) {
    countShema = 0
    modeldata = null;
    var param = {}
    param.idETAT = id;
    get_ajax_data("/etatgensys/asyn_get_config_etat", param, "get_model_from_id_callback");
}

function get_model_from_id_callback(data) {
    $("#ALIAS").val("A");
    aliass = "A";
    modeldata = data["data"];
    $("#sgbd").select2("val", modeldata.sgbd);
    $("#con").val(modeldata.con);
    $("#shemaarea").html("");
    var conn = instance.getConnections();
    $.each(conn,function(index,elm){
        instance.detach(elm);
    })
    instance.reset();
    upmod = true;
    get_shema_up();

}

function get_shema_up() {

    var datas = modeldata["data"];

    if (countShema < datas.length) {
        var param = {}
        param.base = datas[countShema].base;
        param.table = datas[countShema].table;
        param.alias = $("#ALIAS").val();
        param.sgbd = modeldata.sgbd;
        param.con = modeldata.con;
        countShema++;
        if (param.sgbd == "mysql") {
            get_ajax_data("/etatgensys/asyn_get_shema_mysql", param, "get_shema_pupulate");
        } else if (param.sgbd == "db2") {
            get_ajax_data("/etatgensys/asyn_get_shema_db2", param, "get_shema_pupulate");
        }

    } else {
        upmod = false;
    
        $.each(datas, function(index, elm) {
            var al = elm.alias;
            $.each(elm.champs, function(index, elms) {
                var champ = al + "_" + elms.name;
                $("." + champ).find("#desc").val(elms.desc);
                $("." + champ).find("#vals").val(elms.values);
                if(elms.search=="true"){
                    $("." + champ).find("#search").attr("checked","checked");
                }
                if(elms.date=="true"){
                    $("." + champ).find("#date").attr("checked","checked");
                }
                if(elms.afficher=="true"){
                    $("." + champ).find("#afficher").attr("checked","checked");
                }
            })
            
            if (elm.jonct != null && elm.jonct != 0) {
                $.each(elm.jonct, function(index, elms) {
                    var champs = elms.salias + "_" + elms.sname;
                    var champt = elms.talias + "_" + elms.tname;
                    window.instance.connect({
                        source: $("." + champs).find(".connect-out").attr("id"),
                        target: $("." + champt).find(".connect-in").attr("id"),
                    });
                })
            }
        })
    }
}


function get_shema() {

    var param = {}
    param.base = $("#BASE").val();
    param.table = $("#TABLE").val();
    param.sgbd = $("#sgbd").val();
    param.con = $("#con").val();
    param.alias = $("#ALIAS").val();

    if ($("#sgbd").val() == "mysql") {
        get_ajax_data("/etatgensys/asyn_get_shema_mysql", param, "get_shema_pupulate");
    } else if ($("#sgbd").val() == "db2") {
        get_ajax_data("/etatgensys/asyn_get_shema_db2", param, "get_shema_pupulate");
    }

}



function get_shema_pupulate(data) {

    if (data["etat"] == "0") {

        var datas = data["data"];
        var html = ' <div class="col-md-6" id="globcontain">';
        html += '<div class="panel panel-default">'
        html += '<div class="panel-heading"><div class="col-md-6">' + data["table"] + ' => ' + data["alias"] + ' </div>  <button onClick="javascript:$(this).parents(' + "'" + "#globcontain" + "'" + ').remove();$(' + "'" + '#' + data["alias"] + data["table"] + "'" + ').remove();" class="btn btn-danger btn-xs pull-right">Fermer</button><div class="clearfix"></div></div>'
        html += '<div class="panel-body tb" id="carea"  >'
        html += '<input type="hidden" value="' + data["base"] + '" id="basename">'
        html += '<input type="hidden" value="' + data["table"] + '" id="tablename">'
        html += '<input type="hidden" value="' + data["alias"] + '" id="tablealias">'

        $.each(datas, function(index, elm) {

            html += '<div class="input-group input-group-sm connect-table ' + data["alias"] + '_' + elm.NAME + '" >'
            html += '<span style="background-color: #fff" class="input-group-addon"><div style="width: 60px;text-align: left;"><strong>' + elm.NAME + '</strong> : ' + elm.DESCRIPTION + ' </div></span>'
            html += '<span class="input-group-addon tbitem">';
            html += '<input type="hidden" value="' + data["alias"] + '" id="itemalias" >';
            html += '<input type="hidden" value="' + elm.NAME + '" id="itemname" >';
            html += '<input type="hidden" value="' + elm.TYPE + '" id="itemnat" >';
            html += '<input type="hidden" value="' + elm.DESCRIPTION + '" id="description" >';

            if (elm.INDEX == 1) {
                html += '<span style="color:red">Index&nbsp;&nbsp;</span><input type="hidden" value="true" id="index" name="index" />';
            } else {
                html += '<input  type="hidden" value="false" id="index" name="index" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            }
            html += '<div class="col-md-3">Nom de champ :  <input class="form-control" name="desc" id="desc" ></div>'
            html += 'Search<input type="checkbox" id="search" name="search" />&nbsp;&nbsp;'
            html += 'Date<input type="checkbox" id="date" name="date" />&nbsp;&nbsp;'
            html += 'Afficher<input type="checkbox" id="afficher" name="afficher" />&nbsp;&nbsp;'
            html += 'value:<input style="width: 100px; height: 25px;" name="vals" id="vals" type="text" class="form-control"/>'
            html += '<div class="connect-out" al="' + data["alias"] + '" name="' + elm.NAME + '"></div>'
            html += '<div class="connect-in"  al="' + data["alias"] + '" name="' + elm.NAME + '"></div>'
            html += '</span>'

            html += '</div>'
        })

        html += '</div>'
        html += '</div>'
        html += '</div>'

        $("#shemaarea").append(html);

        html = ' <div class="col-md-12" id="' + data["alias"] + data["table"] + '">';
        html += '<div class="panel panel-default">'
        html += '<div class="panel-heading"><div class="col-md-12">' + data["table"] + ' => ' + data["alias"] + ' </div> </div>'
        html += '<div class="panel-body">'


        $.each(datas, function(index, elm) {
            if (elm.INDEX == 1) {
                var stl = "color:red;";
            } else {
                var stl = "";
            }
            html += '<div class="input-group input-group-sm connect-table" id="' + data["alias"] + elm.NAME + '">'
            html += '<span style="background-color: #fff" class="input-group-btn"><button id="' + data["alias"] + "." + elm.NAME + '" style="text-align: left;font-size: 10px !important;width:100%;' + stl + '" class="btn btn-default btn-xs btntable"><strong>' + elm.NAME + '</strong> : ' + elm.DESCRIPTION + ' </button></span>'
            html += '</div>'
        })

        html += '</div>'
        html += '</div>'
        html += '</div>'

        $("#listobj").append(html);

        get_connect();
        if (aliass != "Z") {
            aliass = String.fromCharCode(aliass.charCodeAt(0) + 1)
            aliaimp = aliasss + aliass
        } else {
            if (aliasss == "") {
                aliasss = "A";
                aliass = "A"
                aliaimp = aliasss + aliass
            } else {
                aliasss = String.fromCharCode(aliasss.charCodeAt(0) + 1)
                aliass = "A"
                aliaimp = aliasss + aliass
            }
        }
        $("#ALIAS").val(aliaimp);
        if (upmod == true) {
            get_shema_up();
        }
    }

}


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

function compiler(name) {

    var param = {};
    param.modname = name;
    param.sgbd = $("#sgbd").val();
    param.con = $("#con").val();
    param.data = [];
    $(".tb").each(function() {
        var item = {};
        item.base = $(this).find("#basename").val();
        item.table = $(this).find("#tablename").val();
        item.alias = $(this).find("#tablealias").val();

        item.champs = [];
        item.jonct = [];
        $(this).find(".tbitem").each(function() {
            var itemtab = {};

            itemtab.name = $(this).find("#itemname").val();
            itemtab.type = $(this).find("#itemnat").val();
            itemtab.alias = $(this).find("#itemalias").val();
            itemtab.discription = $(this).find("#description").val();
            itemtab.desc = $(this).find("#desc").val();
            itemtab.index = $(this).find("#index").val();
            itemtab.search = $(this).find("#search").is(":checked");
            itemtab.ai = $(this).find("#ai").is(":checked");
            itemtab.date = $(this).find("#date").is(":checked");
            itemtab.values = $(this).find("#vals").val();
            itemtab.required = $(this).find("#require").is(":checked");
            itemtab.afficher = $(this).find("#afficher").is(":checked");
            itemtab.modifier = $(this).find("#modifier").is(":checked");
            item.champs.push(itemtab);
            var conns = instance.getConnections();
            for (var i = 0; i < conns.length; i++) {
                var sname = $(conns[i].source).attr("name");
                var salias = $(conns[i].source).attr("al");
                var tname = $(conns[i].target).attr("name");
                var talias = $(conns[i].target).attr("al");
                if (tname == $(this).find("#itemname").val() && talias == $(this).find("#itemalias").val()) {
                    var joncttab = {};
                    joncttab.sname = sname;
                    joncttab.salias = salias;
                    joncttab.tname = tname;
                    joncttab.talias = talias;
                    item.jonct.push(joncttab);
                }
            }

        })
        param.data.push(item);
    })

    param.select = [];
    $(".selectent").each(function() {
        var selectitem = {}
        selectitem.exp = $(this).val();
        selectitem.alias = $(this).attr("al");
        param.select.push(selectitem);
    })

    param.where = [];
    $(".whereent").each(function() {
        var whereitem = {}
        whereitem.exp = $(this).val();
        param.where.push(whereitem);
    })

    param.group = $("#editgroup").val();


    if ($("#editorder").val().length > 0) {
        param.order = {}
        param.order = {}
        param.order.exp = $("#editorder").val();
        param.order.type = $("#typeorder").val();
    }


    get_ajax_data("/etatgensys/asyn_compile", param, false);

}




