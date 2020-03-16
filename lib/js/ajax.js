var diag;
var upmoduldiag;
var updatecount;
var compupname;
var modechange;



$().ready(function () {

    $("#perstk").click(function (e) {
        e.stopPropagation();

    })

    $(document).on("dialogclose", function (event, ui) {
        $(".tipsy").remove();
    });

    $("#perstkbtn").click(function (e) {

        e.stopPropagation();
        var param = {}
        param = getFromObj("." + "profile")
        param.periode = $("#perstk").val();
        get_ajax_data("/parametrage/asyn_gen_alert_stk", param, function (data) {
            if (data.etat != "1") {

            }
        })

    })
    
    
     $("#alertdialogp").hide();

    $("#alertdialogp").dialog({
        buttons: {

            "Annuler": function () {
                $(this).dialog("close");
            },
            "Imprimer": function () {
                $("#alertdialogp").printThis({
                    importCSS: true,
                    pageTitle: "Listes des alertes du stock",
                })
            }
        },

        close: function (event, ui) {
            $(this).dialog("close");
        },
        autoOpen: false,
        resizable: true,
        title: "Liste des articles perissable dans un mois",
        modal: true,
        height: $(window).height() * 0.9,
        width: $(window).width() * 0.98,
    })

    $("#alertdialog").hide();

    $("#alertdialog").dialog({
        buttons: {

            "Annuler": function () {
                $(this).dialog("close");
            },
            "Imprimer": function () {
                $("#alertdialog").printThis({
                    importCSS: true,
                    pageTitle: "Listes des alertes du stock",
                })
            },
            "lancer une demmande d'approvisionnement": function () {
                var demaproarra = [];
                var err = false;
                $("#alertdialog table tr.lineitem").each(function () {
                    var obj = JSON.parse($(this).attr("obj"));
                    var qtedem = $(this).find(".demproQte").val()

                    if (qtedem.length > 0) {
                        if (!/^-?\d*\.?\d*$/.test(parseFloat(qtedem))) {
                            err = true;
                            $(this).find(".demproQte").addClass("error");
                        } else {
                            err = false;
                            $(this).find(".demproQte").removeClass("error");
                            obj.demqte = qtedem;
                            demaproarra.push(obj);
                        }
                    } else {
                        $(this).find(".demproQte").addClass("error");
                    }
                })

                if (!err) {
                    if (demaproarra.length != 0) {
                        var param = getFromObj("." + "profile");
                        param.det = demaproarra
                        get_ajax_data("/demapro/asyn_ajout_demapro_auto", param, function (data) {
                            if (data.etat == "1") {
                                $("#alertdialog").dialog("close");
                            }
                        })
                    } else {
                        $.alertMsg("Merci de renseigner les quantités pour les articles déstinés à la demmande d'approvisionnement", "Demmandes d'approvisionnement")
                    }
                } else {
                    $.alertMsg("Merci de verifier les Quantité introduite", "Demmandes d'approvisionnement")
                }
            }
        },

        close: function (event, ui) {
            $(this).dialog("close");
        },
        autoOpen: false,
        resizable: true,
        title: "Liste des stocks d'alerte et de securité",
        modal: true,
        height: $(window).height() * 0.9,
        width: $(window).width() * 0.98,
    })

    $("div.nav_menu ul.navbar-right ").width($(".nav_menu").width() - $("div.nav_menu div.toggle").width() - $("div.nav_menu div.navbar-left").width() - 10);

    var requestFullscreen = function (ele) {
        if (ele.requestFullscreen) {
            ele.requestFullscreen();
        } else if (ele.webkitRequestFullscreen) {
            ele.webkitRequestFullscreen();
        } else if (ele.mozRequestFullScreen) {
            ele.mozRequestFullScreen();
        } else if (ele.msRequestFullscreen) {
            ele.msRequestFullscreen();
        } else {
            console.log('Fullscreen API is not supported.');
        }
    };

    var exitFullscreen = function () {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else {
            console.log('Fullscreen API is not supported.');
        }
    };

    var fsDocButton = $('#fs-doc-button');
    fsDocButton.data('toggleState', 'first');

    fsDocButton.on('click', function (e) {
        e.preventDefault();
        if ($(this).data('toggleState') == 'first') {
            requestFullscreen(document.documentElement);
            $(this).data('toggleState', 'second');
        } else {
            exitFullscreen();
            $(this).data('toggleState', 'first');
        }
    });

    $(document).keydown(function (e) {
        if (e.keyCode == 27) {
            $('#fs-doc-button').data('toggleState', 'second');
        }
    });

    $.fn.select2.defaults.set("theme", "bootstrap");

    $.contextMenu({
        selector: "table",
        callback: function (key, options) {
            tableToExcel("#" + $(this).attr("id"), "Table Excel");
        },
        items: {
            "Exporter Excel": {name: "Exporter Excel", icon: "edit"},
        }
    });


    $("#editprofil").click(function () {
        get_profil();
    })

    if ($("#netmodul").length > 0) {

        $.ajax({
            type: 'POST', // Le type de ma requete
            url: path_base + "/updateMod/asyn_get_net_modul", // L'url vers laquelle la requete sera envoyee
            data: {
                data: {}
            },
            success: function (data, textStatus, jqXHR) {

                var datas = jQuery.parseJSON(data);
                var nettab = datas["data"];
                $("#numnet").html(nettab.length.toString());

                $('#netmodul').click(function () {

                    if (nettab.length != 0) {

                        var html = '<div class="well entstyle">';
                        html += '<table class="tablesorter table table-bordered ">';
                        html += '<thead>';
                        html += '<tr class="fixedhead">';
                        html += '<th>Module Nom</th>';
                        html += '<th>Module Path</th>';
                        html += '<th>Module Task</th>';
                        html += '<th>Module Mode</th>';
                        html += '<th>Date de création</th>';
                        html += '<th>Type</th>';
                        html += '</tr>';
                        html += '</thead>';
                        html += '<tbody id="data_bed">'
                        $.each(nettab, function (index, value) {
                            html += '<tr><td><input class="permmodul" type="hidden" value="' + value.idMODUL + '" >' + value.MODULnom + '</td>';
                            html += '<td>' + value.MODULpath + '</td>';
                            html += '<td>' + value.MODULtask + '</td>';
                            html += '<td>' + value.MODULmod + '</td>';
                            html += '<td>' + value.MODULdcr + '</td>';
                            html += '<td>' + value.MODULtype + '</td></tr>';
                        })
                        html += '</tbody>';
                        html += '</table>';
                        html += '</div>';

                        $("<div></div>").dialog({
                            buttons: {
                                "Supprimer les modules perimé": function () {
                                    var perimtab = {};
                                    var tabmodul = [];
                                    $(".permmodul").each(function () {
                                        tabmodul.push($(this).val());
                                    })

                                    perimtab.perims = tabmodul;
                                    get_ajax_data("/updateMod/asyn_supp_perim_modul", perimtab, false);
                                    $(this).dialog("close");
                                },
                                "Annuler": function () {
                                    $(this).dialog("close");
                                }
                            },
                            close: function (event, ui) {
                                $(this).remove();
                            },
                            resizable: true,
                            title: "System",
                            modal: true,
                            height: 450,
                            width: 850,
                        }).html(html);
                    } else {
                        $.alertMsg("Aucun module périmé n'a étais détécté");
                    }
                })
            },
            error: function (jqXHR, textStatus, errorThrown) {
                aler("Erreur requette");
            }
        })
    }

    $("[datepick]").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy"
    }).mask("00/00/0000", {placeholder: "__/__/____"})


    $("#mag").change(function () {
        var param = {};
        param.mags = $(this).val();
        get_ajax_data("/main/asyn_change_mag", param, false);
    })

    $("#exe").change(function () {
        var param = {};
        param.exes = $(this).val();
        get_ajax_data("/main/asyn_change_exe", param, false);
    })


    check_elm();


    if (typeof $.fn.validate != 'undefined'
            && typeof $.fn.tipsy != 'undefined') {

        $.validator.setDefaults({
            submitHandler: function (form) {
                if (typeof $(form).attr("direct") !== typeof undefined) {

                    var params = getFromObj("#" + $(form).attr('id'));

                    if (typeof $(form).attr("addid") !== typeof undefined) {

                        var prm = $(form).attr("addid").split(" ");
                        $.each(prm, function (index, value) {
                            if ($("#" + value).length > 0) {
                                params[value] = $("#" + value).val();
                            }
                        })
                    }

                    if (typeof $(form).attr('callback') != 'undefined') {
                        var callback = $(form).attr('callback');
                    } else {
                        var callback = false;
                    }

                    get_ajax_data("/" + window.modul + "/asyn_" + $(form).attr('action'), params, function (data) {

                        if (data.etat == "1" || data.etat == "0") {
                            if (typeof $(form).attr("close") !== typeof undefined) {
                                $("#" + $(form).parents(".ui-dialog-content").attr("id")).dialog("close")
                            }
                            if (typeof $(form).attr("remove") !== typeof undefined) {
                                $("#" + $(form).parents(".ui-dialog-content").attr("id")).remove();
                            }
                        }

                        if (callback != false) {
                            eval(callback)(data);
                        }
                    });

                } else {
                    eval($(form).attr('action'))();
                }
            },
            success: function (label, element) {
                $(element).each(function () {
                    $(this).tipsy("hide").removeAttr('original-title');
                });
            },
            errorPlacement: function (error, element) {
                element.attr('title', error.text());
                element.tipsy({
                    fade: true,
                    gravity: 'w'
                });
                element.tipsy("show");
            }
        });
    }

    $('#newmodul').click(function () {
        $("<div></div>").dialog({
            resizable: false,
            height: 250,
            width: 300,
            buttons: {
                "Ok": function () {
                    var param = {};
                    param.nommodule = $("#modulenom").livequery(function () {
                    }).val();
                    get_ajax_data("/main/asyn_ajout_module", param, false);
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).remove();
            },
            resizable: false,
            title: "crée un nouveau module vide",
            modal: true
        }).html('<div class="col-lg-9 col-lg-offset-2"><div class="row"><div class="input-group input-group-sm top1"><span class="input-group-addon">Nom :</span> <input type="text" class="form-control" name="modulenom" id="modulenom" /></div></div></div>');
    })

    $("#updateMod").click(function () {
        updatecount = 0;
        compupname = ""
        modechange = "ad";

        $.ajax({
            type: 'POST', // Le type de ma requete
            url: path_base + "/updateMod/asyn_reset_update_state",
            data: {
                data: {}
            },
            success: function (data, textStatus, jqXHR) {

                upmoduldiag = $("<div></div>").dialog({
                    buttons: {"Ok": function () {
                            $(this).dialog("close");
                        }},
                    close: function (event, ui) {
                        $(this).remove();
                    },
                    stack: false,
                    resizable: false,
                    title: "Mise à jour des composants",
                    modal: true,
                    height: 650,
                    width: 700,
                }).html('<div id="compupdate"></div>');

                upmoduldiag.dialog("open");

                var param = {};
                param.mode = "ad";
                $.ajax({
                    type: 'POST',
                    url: path_base + "/updateMod/asyn_get_com_arborecense",
                    data: {
                        data: param
                    },
                    success: function (data, textStatus, jqXHR) {

                        var com = jQuery.parseJSON(data);
                        $.each(com, function (index, value) {
                            var param = {};
                            param.arb = value;
                            get_ajax_data("/updateMod/asyn_update_ad", param, "update_comp_callback");
                        })

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        aler("Erreur requette");
                    }
                })


                param.mode = "front";
                $.ajax({
                    type: 'POST',
                    url: path_base + "/updateMod/asyn_get_com_arborecense",
                    data: {
                        data: param
                    },
                    success: function (data, textStatus, jqXHR) {

                        var com = jQuery.parseJSON(data);
                        $.each(com, function (index, value) {

                            var param = {};
                            param.arb = value;
                            get_ajax_data("/updateMod/asyn_update_front", param, "update_comp_callback");
                        })

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Erreur requette");
                    }
                })

            },
            error: function (jqXHR, textStatus, errorThrown) {
                aler("Erreur requette");
            }
        })
    })

    $("[consult]").each(function () {
        var param = {};
        var elmselect = $(this)
        elmselect.html("");
        var prm = $(this).attr("consult").split(":");
        if (typeof prm[0] != 'undefined' && typeof prm[1] != 'undefined' && typeof prm[2] != 'undefined') {
            param.model = prm[0]
            param.id = prm[1]
            param.lib = prm[2]
            var ids = prm[1];
            var libs = prm[2];
            get_ajax_data("/main/asyn_getautoselect", param, function (data) {
                $.each(data.data, function (index, value) {
                    elmselect.append(value[libs]);
                })

                $(elmselect).removeAttr("consult");
            });
        }
    })

    modelauto()

    $("[selectsearch").each(function () {
        var prm = $(this).attr("selectsearch").split(":");
        var params = {};
        params.model = prm[0]
        params.id = prm[1]
        params.lib = prm[2]
        params.aff = prm[3]
        params.cond = prm[4]

        if (typeof $(this).attr("dep") != 'undefined') {
            var dep = {}
            var depparam = $(this).attr("dep").split(":");
            dep.item = depparam[0];
            dep.val = $("#" + depparam[1]).val();
            params.dep = dep;
        }


        var elmselect = $(this)
        var ids = prm[1];
        var libs = prm[2];

        if (typeof prm[0] != 'undefined' && typeof prm[1] != 'undefined' && typeof prm[2] != 'undefined') {

            get_ajax_data("/main/asyn_getautoselect", params, function (data) {
                elmselect.append('<option value=""></option>');
                $.each(data.data, function (index, value) {
                    elmselect.append('<option value="' + value[ids] + '">' + value[libs] + '</option>');
                })
            });

            var elmforserch = $(this)

            $(this).select2({
                ajax: {
                    url: path_base + "/main/asyn_getautoselectsearch",
                    dataType: 'json',
                    delay: 250,
                    method: "POST",
                    placeholder: "Select a state",
                    data: function (param) {
                        params.r = param.term
                        params.page = params.page
                        params.dep = "d"

                        if (typeof $(this).attr("dep") != 'undefined') {
                            var dep = {}
                            var depparam = $(this).attr("dep").split(":");
                            dep.item = depparam[0];
                            dep.val = $("#" + depparam[1]).val();
                            params.dep = dep;
                        }


                        return params;
                    },
                    processResults: function (data, params) {
                        var searchTerm = elmforserch.data("select2").$dropdown.find("input").val();

                        if (data.total_count == 1 && searchTerm == data.items[0].id) {

                            elmforserch.append($("<option />")
                                    .attr("value", data.items[0].id)
                                    .html(data.items[0].name)
                                    ).val(data.items[0].id).trigger("change").select2("close");
                            elmforserch.focusNextInputField()

                        }

                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                minimumInputLength: 1,
                templateResult: function (repo) {
                    if (repo.loading)
                        return repo.text;

                    var arraff = params.aff.split(";")

                    var markup = '<div class="clearfix"></div>'

                    $.each(arraff, function (index, value) {
                        markup += '<div class="col-xs-4">' + repo[value] + '</div>'
                    })

                    markup += '<div class="clearfix"></div>'

                    return markup;

                },
                templateSelection: function (repo) {
                    return repo.name || repo.text;
                }
            });

            $(elmselect).removeAttr("selectsearch");
        }
    })



    $("#deconection").click(function () {
        get_ajax_data("/main/asyn_deconection", param = {}, "redirect_after_delog");
    })

    $("#deconection_mn").click(function () {
        get_ajax_data("/main/asyn_deconection", param = {}, "redirect_after_delog");
    })


    $("#raf").click(function () {
        get_ajax_data("/main/asyn_raf", param = {}, "refrech");
    })

    $("#clickalert").click(function () {
        get_stkalert()
    })
})

function get_stkalert() {

    var param = {}
    param = getFromObj("." + "profile")
    param.periode = $("#perstk").val();
    get_ajax_data("/parametrage/asyn_get_nbalert_stk", param, function (data) {
        if (data.etat == "0") {
         
            $("#nbalert").html(data.data[0] + data.data[1] + data.data[2]+ + data.data[3])
            var html = ""
            if (data.data[0] != 0) {
                html += '<li>'
                html += '<a id="artalert">'
                html += '<span class="image">'
                html += '</span>'
                html += '<span>'
                html += '<span>Article en seuil d\'alerte : </span>'
                html += '<span class="time">' + data.data[0] + '</span>'
                html += '</span>'
                html += '<span class="message">'
                html += 'la commande n\'est pas urgente'
                html += '</span>'
                html += '</a>'
                html += '</li>'
            }

            if (data.data[1] != 0) {
                html += '<li>'
                html += '<a  id="artsecurite">'
                html += '<span class="image">'
                html += '</span>'
                html += '<span>'
                html += '<span>Article en seuil de securité : </span>'
                html += '<span class="time">' + data.data[1] + '</span>'
                html += '</span>'
                html += '<span class="message">'
                html += 'la Commande est urgente !'
                html += '</span>'
                html += '</a>'
                html += '</li>'
            }

            if (data.data[2] != 0) {

                html += '<li>'
                html += '<a  id="artrupture">'
                html += '<span class="image">'
                html += '</span>'
                html += '<span>'
                html += '<span>Article en rupture de sock : </span>'
                html += '<span class="time">' + data.data[2] + '</span>'
                html += '</span>'
                html += '<span class="message">'
                html += 'Lancement imperatif de commande !'
                html += '</span>'
                html += '</a>'
                html += '</li>'
            }
            
             if (data.data[3] != 0) {

                html += '<li>'
                html += '<a  id="artperim">'
                html += '<span class="image">'
                html += '</span>'
                html += '<span>'
                html += '<span>Articles arrivé à péromption (1 mois): </span>'
                html += '<span class="time">' + data.data[3] + '</span>'
                html += '</span>'
                html += '<span class="message">'
                html += 'Verification des articles!'
                html += '</span>'
                html += '</a>'
                html += '</li>'
            }

            $("#menu1").html(html);

            $("#artalert").click(function () {
                var param = {}
                param = getFromObj("." + "profile")
                param.mode = "A"
                get_ajax_data("/parametrage/asyn_get_alert_stk", param, function (data) {
                    $.buildTable("#alertdialog", "Liste des alertes du stock", data.data, []);
                    $("#alertdialog table tr.lineitem").each(function () {
                        obj = JSON.parse($(this).attr("obj"));
                        var html = '<td>'
                        html += '<input class="demproQte"  type="text"  placeholder="Stk min : ' + obj.b_artStockminimale + '">'
                        html += '</td>'
                        $(this).append(html);
                    })
                    $("#alertdialog").dialog("open");

                })
            })
            
            
              $("#artperim").click(function () {
                var param = {}
                param = getFromObj("." + "profile")
                param.mode = "P"
                get_ajax_data("/parametrage/asyn_get_alert_stk", param, function (data) {
                    $.buildTable("#alertdialogp", "Liste des articles périssable le mois prochain", data.data, []);
            
                    $("#alertdialogp").dialog("open");

                })
            })

            $("#artsecurite").click(function () {
                var param = {}
                param = getFromObj("." + "profile")
                param.mode = "S"
                get_ajax_data("/parametrage/asyn_get_alert_stk", param, function (data) {
                    $.buildTable("#alertdialog", "Liste des alertes du stock", data.data, []);
                    $("#alertdialog table tr.lineitem").each(function () {
                        obj = JSON.parse($(this).attr("obj"));
                        var html = '<td>'
                        html += '<input class="demproQte"  type="text"  placeholder="Stk min : ' + obj.b_artStockminimale + '">'
                        html += '</td>'
                        $(this).append(html);
                    })
                    $("#alertdialog").dialog("open");
                })
            })

            $("#artrupture").click(function () {
                var param = {}
                param = getFromObj("." + "profile")
                param.mode = "R"
                get_ajax_data("/parametrage/asyn_get_alert_stk", param, function (data) {
                    $.buildTable("#alertdialog", "Liste des alertes du stock", data.data, []);
                    $("#alertdialog table tr.lineitem").each(function () {
                        obj = JSON.parse($(this).attr("obj"));
                        var html = '<td>'
                        html += '<input class="demproQte"  type="text"  placeholder="Stk min : ' + obj.b_artStockminimale + '">'
                        html += '</td>'
                        $(this).append(html);
                    })
                    $("#alertdialog").dialog("open");
                })
            })

        }
    })
}

function modelauto(elm) {

    if (typeof elm == 'undefined') {
        var elm = $("[selecmodel]");
    } else {
        var elm = $(elm);
    }

    elm.each(function () {
        var param = {};
        var elmselect = $(this)
        elmselect.html("");

        if (typeof $(this).attr("cachit") != "undefined") {
            if ($(this).attr("cachit") == "") {
                var uidcach = guid();
                window[uidcach] = $(this).attr("selecmodel");
                $(this).attr("cachit", uidcach)
                var prm = $(this).attr("selecmodel").split(":");
            } else {
                var prm = window[$(this).attr("cachit")].split(":");
            }

        } else {
            var prm = $(this).attr("selecmodel").split(":");
        }
        var callback = false;
        if (typeof $(this).attr("callback") != "undefined") {
            callback = true;
            var callbackfn = $(this).attr("callback");
        }

        if (typeof prm[0] != 'undefined' && typeof prm[1] != 'undefined' && typeof prm[2] != 'undefined') {
            param.model = prm[0]
            param.id = prm[1]
            param.lib = prm[2]
            var ids = prm[1];
            var libs = prm[2];
            get_ajax_data("/main/asyn_getautoselect", param, function (data) {
                elmselect.append('<option value=""></option>');
                $.each(data.data, function (index, value) {
                    elmselect.append('<option value="' + value[ids] + '">' + value[libs] + '</option>');
                })
                $(elmselect).select2();
                $(elmselect).removeAttr("selecmodel");
                if (callback) {
                    eval(callbackfn)();
                }
            });
        }

        var elmbtn = $('<button type="button" class="btn btn-sm btn-success">+</button>')
        //var btn = $(this).after(elmbtn)
        elmbtn.click(function () {

            var pr = {}
            pr.model = prm[0]
            pr.id = prm[1]
            pr.lib = prm[2]

            var html = '<div class="col-md-12 col-sm-12">'
            var uid = guid();
            html += '<div class="form-group "><label class="col-sm-3 control-label" for="' + prm[2] + '">Valeur</label>' +
                    '<div class="col-sm-7">' +
                    '<input  type="text" class="form-control" id="' + prm[2] + uid + '" name="' + prm[2] + '" placeholder="">'
            '</div>' +
                    '</div>'
            html += '</div>'
            html += '</div>'
            html += '</div>'


            $("<div></div>").dialog({
                buttons: {"Ok": function () {
                        pr.val = $("#" + prm[2] + uid).val();
                        get_ajax_data("/main/asyn_add_entitie", pr, function (data) {
                            elmselect.html("");
                            elmselect.append('<option value=""></option>');
                            $.each(data.data, function (index, value) {
                                elmselect.append('<option value="' + value[ids] + '">' + value[libs] + '</option>');
                            })
                        });
                        $(this).dialog("close");
                    }},
                close: function (event, ui) {
                    $(this).remove();
                },
                stack: false,
                resizable: false,
                title: "Ajouter une valeur",
                modal: true,
                height: 200,
                width: 400,
            }).html(html);

        })

    })
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
}

function update_comp_callback(data) {

    var msg = data["msg"];

    if (updatecount != 0) {

        if (compupname != data["comp"]) {
            compupname = data["comp"];
            $("#compupdate").append('<br><h5>Composant : ' + compupname + '</h5>')
        }
        updatecount++;
    } else {
        $("#compupdate").append('<br><h5>******************************************* Back Office ********************************************</h5>')
        modechange = data["mode"];
        compupname = data["comp"];
        $("#compupdate").append('<div><h5>Composant : ' + compupname + '</h5></div>')
        updatecount++;
    }

    if (modechange != data["mode"]) {
        $("#compupdate").append('<br><h5>******************************************* Front Office ********************************************</h5>')
        modechange = data["mode"]
    }

    $("#compupdate").append('<div id="' + msg + '">' + msg + '</div>')

}

$(document).ajaxStart(function () {
    tpl_loading();
})

$(document).ajaxStop(function () {
    tpl_done();
})

function refrech() {
    location.reload();
}

function get_profil() {
    get_ajax_data("/main/asyn_get_profil_det", param = {}, "get_prof_det_callback");
}

function get_prof_det_callback(data) {


    var html = '<form id="frm_mod_util" name="frm_mod_util"  action="sauv_profil" >';
    html += '<div class="col-lg-7 col-lg-offset-1 top1 entstyle">'
    html += '<div class="input-group input-group-sm top1 marg_top_1">'
    html += '<span class="input-group-addon">Login :</span> <input type="text" class="form-control required" value="' + data["data"][0]["UTILOG"] + '" name="UTILOG" id="UTILOG" />'
    html += '</div><br/>'
    html += '<div class="input-group input-group-sm top1 marg_top_1">'
    html += '<span class="input-group-addon">Passe :</span> <input type="password" class="form-control required" name="UTIPWD" id="UTIPWD" /><br/>'
    html += '</div><br/>'
    html += '<div class="input-group input-group-sm top1 marg_top_1">'
    html += '<span class="input-group-addon">Passe 2:</span> <input type="password" class="form-control required" name="UTIPWD2" id="UTIPWD2" /><br/>'
    html += '</div><br/>'
    html += '<input type="hidden" class="form-control" value="' + data["data"][0]["IDUTIL"] + '" name="IDUTIL" id="IDUTIL" />'
    html += '</div>'
    html += '</form>'

    $("<div></div>").dialog({
        buttons: {"Sauvegarder": function () {
                $("#frm_mod_util").submit();
                diag = $(this);
            }},
        close: function (event, ui) {
            $(this).remove();
        },
        width: 700,
        height: 350,
        autoOpen: true,
        resizable: false,
        title: "Modification des informations d'authentification",
        modal: true
    }).html(html);

    $("#frm_mod_util").validate({
        rules: {
            UTILOG: {
                remote: {
                    url: path_base + "/main/asyn_get_utilog",
                    type: "post",
                    data: {
                        utilog: function () {
                            return $("#UTILOG").val();
                        }
                    }
                }
            },
            UTIPWD2: {
                equalTo: "#UTIPWD"
            }
        },
        message: {
            UTILOG: {
                remote: "Se login existe déja pour un autre utilisateur"
            },
            UTIPWD2: {
                equalTo: "#mot de passe non identique"
            }
        }
    });

    $(document).scroll(function () {
        $(".preload").center();
    })



}

function sauv_profil() {
    var param = getFromObj("#frm_mod_util");
    get_ajax_data("/main/asyn_sauv_profil", param, "sauv_profil_callback");
}


function sauv_profil_callback(data) {
    diag.remove();
}


function disableLastExe() {
    get_ajax_data("/main/asyn_get_curent_exe", param = {}, "disableLastExecallback");
}

function disableLastExecallback(data) {
    $("#exe").children().each(function () {
        if ($(this).val() != data["exe"]) {
            $(this).attr("disabled", "");
            $(this).removeAttr("selected");
        } else {
            $(this).attr("selected", "selected");
        }
    })
}

function redirect_after_delog() {
    $(document).attr("location", path_base + "/login");
}

function getFromObj(form) {
    var params = {};
    var input = $(form + " :input").each(function () {
        params[$(this).attr("name")] = $(this).val();
    });
    return params;
}

/*
 function get_ajax_data(path, param, callback, extra) {
 
 
 
 $.ajax({
 type: 'POST', // Le type de ma requete
 dataType: "html",
 url: path_base + path, // L'url vers laquelle la requete sera envoyee
 data: {
 data: param
 },
 success: function(data, textStatus, jqXHR) {
 try {
 var datas = jQuery.parseJSON(data);
 $.getServerMessage(datas);
 if (callback != false) {
 if (extra) {
 eval(callback)(datas, extra);
 } else {
 eval(callback)(datas);
 }
 }
 
 } catch (e) {
 $.alertMsg("Une erreur fatale c'est produite lors de la transaction avec le serveur merci de contacté l'administrateur de l'application - Detail : <br/> calback = " + callback + "  <br/>  Variables  = " + JSON.stringify(param) + " <br/>Reponse : <div class='well' style='color:#000'>" + data + "</div> client : <div class='well' style='color:#000'>" + e.toString() + " : " + e.stack + "</div>", "Erreur System", 400, 700);
 var param = {etat: 2};
 $.getServerMessage(param);
 }
 
 
 },
 error: function(jqXHR, textStatus, errorThrown) {
 tpl_done();
 return false;
 }
 })
 } */



function get_ajax_data_file(path, param, file, callback, extra) {

    var formData = new FormData();

    $.each(param, function (index, value) {
        formData.append(index, value);
    })

    formData.append('file', $('#' + file)[0].files[0]);

    $.ajax({
        type: 'POST',
        url: path_base + path,
        data: formData,
        processData: false,
        contentType: false,
        success: function (data, textStatus, jqXHR) {
            try {
                var datas = jQuery.parseJSON(data);
                $.getServerMessage(datas);
                if (callback != false) {
                    if (extra) {
                        if (typeof callback === "string")
                            eval(callback)(datas, extra);
                        else
                            callback(datas);
                    } else {
                        if (typeof callback === "string")
                            eval(callback)(datas);
                        else
                            callback(datas);
                    }
                }

            } catch (e) {
                get_ajax_data("/main/asyn_isadmin", param, function (datas) {
                   
                    if (datas.etats == "1") {
                        $.alertMsg("Une erreur fatale c'est produite lors de la transaction avec le serveur merci de contacté l'administrateur de l'application - Detail : <br/> calback = " + callback + "  <br/>  Variables  = " + JSON.stringify(param) + " <br/>Reponse : <div class='well' style='color:#000'>" + data + "</div> client : <div class='well' style='color:#000'>" + e.toString() + " : " + e.stack + "</div>", "Erreur System", 400, 700);
                        var param = {etat: 2};
                        $.getServerMessage(param);
                    } else {
                        $.alertMsg("L'acction n'a pas abouti");
                    }
                })
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            tpl_done();
            return false;
        }
    })
}


function get_ajax_data(path, param, callback, extra) {

    $.ajax({
        type: 'POST', // Le type de ma requete
        dataType: "html",
        url: path_base + path, // L'url vers laquelle la requete sera envoyee
        data: {
            data: param
        },
        success: function (data, textStatus, jqXHR) {
            try {
                var datas = jQuery.parseJSON(data);
                $.getServerMessage(datas);
                if (callback != false) {
                    if (extra) {
                        if (typeof callback === "string")
                            eval(callback)(datas, extra);
                        else
                            callback(datas);
                    } else {
                        if (typeof callback === "string")
                            eval(callback)(datas);
                        else
                            callback(datas);
                    }
                }

            } catch (e) {
                 get_ajax_data("/main/asyn_isadmin", param, function (datas) {
                    if (datas.etats =="1") {
                        $.alertMsg("Une erreur fatale c'est produite lors de la transaction avec le serveur merci de contacté l'administrateur de l'application - Detail : <br/> calback = " + callback + "  <br/>  Variables  = " + JSON.stringify(param) + " <br/>Reponse : <div class='well' style='color:#000'>" + data + "</div> client : <div class='well' style='color:#000'>" + e.toString() + " : " + e.stack + "</div>", "Erreur System", 400, 700);
                        var param = {etat: 2};
                        $.getServerMessage(param);
                    } else {
                        $.alertMsg("L'action n'a pas abouti","Message du Systéme");
                    }
                })
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            tpl_done();
            return false;
        }
    })
}

function check_elm() {
    if ($("[dr]").length > 0) {
        $("[dr]").each(function () {
            $(this).hide();
        })

        get_ajax_data("/main/asyn_get_all_right", null, "check_elm_callback");
    }
}

function check_elm_callback(data) {
    var datamark = data["data"]
    var marks = [];
    $.each(datamark, function (index, datad) {
        marks.push(datad["DROITmark"]);
    })
    $("[dr]").each(function () {
        var mark = $(this).attr("dr");
        if ($.inArray(mark, marks) == -1) {
            $(this).remove();
        } else {
            $(this).show();
        }
    })
}


function tpl_loading() {
    $(":button").attr("disabled", true);
    $(".ajaxspin").show();
    $(".preload").center();
    $(".preload").show();
}


function tpl_done() {
    $(":button").attr("disabled", false);
    $(".ajaxspin").hide();
    $(".preload").hide();
}

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] == obj) {
                return i;
            }
        }
        return -1;
    }
}


var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
            , base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }
    , format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
        })
    }
    return function (table, name) {
        if (!table.nodeType)
            table = $(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.html()}
        window.location.href = uri + base64(format(template, ctx))
    }
})()

function dateformat(date) {

    return date.toString().substring(8, 10) + "/" + date.toString().substring(5, 7) + "/" + date.toString().substring(0, 4);
}

function dateformatmois(date) {
    return  date.toString().substring(4, 6) + "/" + date.toString().substring(0, 4);
}


function trace(obj) {
    $.alertMsg(JSON.stringify(obj), "Trace");
}

function sgsNumber(number) {
    return  numeral(number).format('0.000');
}

function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}




$.extend($.ui.dialog.overlay, {create: function (dialog) {
        if (this.instances.length === 0) {
            // prevent use of anchors and inputs
            // we use a setTimeout in case the overlay is created from an
            // event that we're going to be cancelling (see #2804)
            setTimeout(function () {
                // handle $(el).dialog().dialog('close') (see #4065)
                if ($.ui.dialog.overlay.instances.length) {
                    $(document).bind($.ui.dialog.overlay.events, function (event) {
                        var parentDialog = $(event.target).parents('.ui-dialog');
                        if (parentDialog.length > 0) {
                            var parentDialogZIndex = parentDialog.css('zIndex') || 0;
                            return parentDialogZIndex > $.ui.dialog.overlay.maxZ;
                        }

                        var aboveOverlay = false;
                        $(event.target).parents().each(function () {
                            var currentZ = $(this).css('zIndex') || 0;
                            if (currentZ > $.ui.dialog.overlay.maxZ) {
                                aboveOverlay = true;
                                return;
                            }
                        });

                        return aboveOverlay;
                    });
                }
            }, 1);

            // allow closing by pressing the escape key
            $(document).bind('keydown.dialog-overlay', function (event) {
                (dialog.options.closeOnEscape && event.keyCode
                        && event.keyCode == $.ui.keyCode.ESCAPE && dialog.close(event));
            });

            // handle window resize
            $(window).bind('resize.dialog-overlay', $.ui.dialog.overlay.resize);
        }

        var $el = $('<div></div>').appendTo(document.body)
                .addClass('ui-widget-overlay').css({
            width: this.width(),
            height: this.height()
        });

        (dialog.options.stackfix && $.fn.stackfix && $el.stackfix());

        this.instances.push($el);
        return $el;
    }});


function addRules(rulesObj) {
    for (var item in rulesObj) {
        $('#' + item).rules('add', rulesObj[item]);
    }
}

function removeRules(rulesObj) {
    for (var item in rulesObj) {
        $('#' + item).rules('remove');
    }
}

function readURLimage(input, image) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(image).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function setimage(input, image) {
    $(image).attr('src', input);
}

function actualise_stock() {
    var param = {}
    param = getFromObj("." + "profile")
    get_ajax_data("/parametrage/asyn_state_stktable", param, function (data) {
        if (data.etat != "0") {
            get_ajax_data("/parametrage/asyn_gen_stktable", param, function (data) {
                if (data.etat == "0") {

                } else {

                }
            })
        }
    })
}