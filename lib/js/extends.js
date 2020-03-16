(function($) {

    window.theme = {
        color: [
            '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
            '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
        ],
        title: {
            itemGap: 8,
            textStyle: {
                fontWeight: 'normal',
                color: '#408829'
            }
        },
        dataRange: {
            color: ['#1f610a', '#97b58d']
        },
        toolbox: {
            color: ['#408829', '#408829', '#408829', '#408829']
        },
        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.5)',
            axisPointer: {
                type: 'line',
                lineStyle: {
                    color: '#408829',
                    type: 'dashed'
                },
                crossStyle: {
                    color: '#408829'
                },
                shadowStyle: {
                    color: 'rgba(200,200,200,0.3)'
                }
            }
        },
        dataZoom: {
            dataBackgroundColor: '#eee',
            fillerColor: 'rgba(64,136,41,0.2)',
            handleColor: '#408829'
        },
        grid: {
            borderWidth: 0
        },
        categoryAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },
        valueAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitArea: {
                show: true,
                areaStyle: {
                    color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },
        timeline: {
            lineStyle: {
                color: '#408829'
            },
            controlStyle: {
                normal: {color: '#408829'},
                emphasis: {color: '#408829'}
            }
        },
        k: {
            itemStyle: {
                normal: {
                    color: '#68a54a',
                    color0: '#a9cba2',
                    lineStyle: {
                        width: 1,
                        color: '#408829',
                        color0: '#86b379'
                    }
                }
            }
        },
        map: {
            itemStyle: {
                normal: {
                    areaStyle: {
                        color: '#ddd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                },
                emphasis: {
                    areaStyle: {
                        color: '#99d2dd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                }
            }
        },
        force: {
            itemStyle: {
                normal: {
                    linkStyle: {
                        strokeColor: '#408829'
                    }
                }
            }
        },
        chord: {
            padding: 4,
            itemStyle: {
                normal: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                },
                emphasis: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                }
            }
        },
        gauge: {
            startAngle: 225,
            endAngle: -45,
            axisLine: {
                show: true,
                lineStyle: {
                    color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                    width: 8
                }
            },
            axisTick: {
                splitNumber: 10,
                length: 12,
                lineStyle: {
                    color: 'auto'
                }
            },
            axisLabel: {
                textStyle: {
                    color: 'auto'
                }
            },
            splitLine: {
                length: 18,
                lineStyle: {
                    color: 'auto'
                }
            },
            pointer: {
                length: '90%',
                color: 'auto'
            },
            title: {
                textStyle: {
                    color: '#333'
                }
            },
            detail: {
                textStyle: {
                    color: 'auto'
                }
            }
        },
        textStyle: {
            fontFamily: 'Arial, Verdana, sans-serif'
        }
    };

    var _oldhide = $.fn.hide;
    $.fn.hide = function(speed, callback) {
        $(this).trigger('hide');
        return _oldhide.apply(this, arguments);
    }

    $.fn.reverseText = function(params) {
    };

    $.fn.center = function() {
        this.css("position", "absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + "px");
        return this;
    }

    $.fn.focusNextInputField = function() {
        return this.each(function() {
            var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select').not(':hidden');
            var index = fields.index(this);
            if (index > -1 && (index + 1) < fields.length) {
                fields.eq(index + 1).focus().val("1").trigger("change");
            }
            else {
                fields.first().focus().val("1").trigger("change");
            }
            return false;
        });
    };

})(jQuery);
$().ready(function() {

    $.extend({btn_ischeck: function(slct) {
            if ($(slct).attr("rel") == "check")
                return true;
            else if ($(slct).attr("rel") == "uncheck")
                return false;
        }
    });

    $.extend({chk_btnstate: function(slct, onoff) {

            if (onoff)
                $(slct).attr("rel", "check").removeClass("btn btn-danger btn-xs").addClass("btn btn-success btn-xs active");
            else
                $(slct).attr("rel", "uncheck").removeClass("btn btn-success btn-xs active").addClass("btn btn-danger btn-xs");
        }
    });

    $.extend({buildTable: function(elm, titre, table, btn, tot) {

            var html = '<table class="tablesorter  table table-striped  table-condensed tablesorter-bootstrap datatab" id="expexcel' + guid() + '">'
            html += '<thead>'

            if (titre != "") {
                html += '<tr>'
                html += '<th colspan="20" ><center><h3>' + titre + '</h3></center></th>'
                html += '</tr>'
                html += '<tr>'
            }

            $.each(table.head, function(index, value) {
                html += '<th>' + index + '</th>';
            })

            if (btn.length > 0) {
                html += '<th>Operations</th>';
            }

            html += '</tr>'
            html += '</thead>'
            html += '<tbody>'

            $.each(table.data, function(ind, val) {
                var str = JSON.stringify(val).replace(/'/g, " ")
                html += "<tr class='lineitem' obj='" + str + "'>"
                var uniqidval = guid();
                window[uniqidval] = val;
                $.each(table.head, function(index, value) {
                    $.each(val, function(inds, vals) {
                        if (inds == value) {
                            if ($.inArray(inds, tot) != -1) {
                                if (typeof window[inds] == "undefined") {
                                    window[inds] = 0;
                                    if (vals != null && vals != '') {
                                        window[inds] += parseFloat(vals)
                                    }
                                } else {
                                    if (vals != null && vals != '') {
                                        window[inds] += parseFloat(vals)
                                    }
                                }
                            }
                            if (vals != 0 && vals != '' && vals != null) {

                                if (typeof vals != "object") {
                                    var expr = /_pj/;
                                    if (expr.test(inds.toString())) {
                                        html += '<td class="' + inds + '"><a href="' + path_base + vals + '">Piéce jointe</td>';
                                    } else {
                                        html += '<td class="' + inds + '">' + vals + '</td>';
                                    }
                                } else {
                                    if (vals.date != 'undefined') {
                                        html += '<td class="' + inds + '">' + vals.date.split(" ")[0] + '</td>';
                                    } else {
                                        html += '<td class="' + inds + '">0</td>';
                                    }
                                }

                            } else {
                                if (typeof tot !== "undefined") {
                                    if ($.inArray(inds, tot) != -1) {
                                        html += '<td class="' + inds + '">0</td>';
                                    } else {
                                        html += '<td class="' + inds + '">0</td>';
                                    }
                                } else {
                                    html += '<td class="' + inds + '">0</td>';
                                }
                            }

                        }
                    })
                })

                if (btn.length > 0) {
                    html += '<td class="col-md-3">'

                    $.each(btn, function(index, value) {

                        var param = {}
                        param.datarefval = window[uniqidval];
                        param.consulter = table.consult

                        if (typeof value.titre == 'function') {
                            var titreho = value.titre(param);
                        }

                        if ((typeof value.titre == 'function' && titreho != "") || (typeof value.titre != 'function' && value.titre != 'undefined')) {
                            if (typeof value.dr != 'undefined') {
                                var dr = 'dr="' + value.dr + '"'
                            } else {
                                var dr = ""
                            }
                            var display = true;
                           
                            if (typeof value.condition == 'function' && typeof value.condition != 'undefined') {
                                var display = value.condition(param);
                            } else {
                                var display = true;
                            }
                            if (display) {
                                html += ' <button ' + dr + '  idval="' + uniqidval + '" class="btn btn-primary btn-xs ' + value.class + '" '

                                $.each(value.attr, function(index, value) {
                                    html += value + ' ="' + val[value] + '" '
                                })


                                if (typeof value.titre == 'function') {
                                    html += '> ' + value.titre(param);
                                } else {
                                    html += '> ' + value.titre
                                }
                                ;


                                html += '</button>'
                            }
                        }

                    })

                    html += '</td>'
                }

                html += '</tr>'


            })

            if (typeof tot !== "undefined") {
                $.each(table.head, function(index, value) {
                    if ($.inArray(value, tot) != -1) {
                        html += '<th class="bftot">' + window[value] + '</th>';
                        window[value] = 0;
                    } else {
                        html += '<th></th>';
                    }
                })
            }

            html += '</tbody>'
            html += '</table>'
            $(elm).html(html);

            check_elm();

            $(".datatab").DataTable();
           

            $(".bftot").first().prev().html("Totale");

            $.each(btn, function(index, value) {
                $("." + value.class).click(function() {
                    var param = {}
                    var elm = $(this)
                    $.each(value.attr, function(index, val) {
                        param[val] = elm.attr(val);
                    })
                    param.datarefval = window[elm.attr("idval")];
                    param.consulter = table.consult
                    value.fnct(param);
                });
            })

        }
        
        
    });


    $(".btnchk").each(function() {
        if ($.btn_ischeck(this)) {
            $(this).attr("rel", "check").removeClass("btn btn-danger btn-xs").addClass("btn btn-success btn-xs active");
        } else {
            $(this).attr("rel", "uncheck").removeClass("btn btn-success btn-xs active").addClass("btn btn-danger btn-xs");
        }
    })


    $(".btnchk").click(function() {
        if ($(this).attr("rel") == "uncheck")
            $(this).attr("rel", "check").removeClass("btn btn-danger btn-xs").addClass("btn btn-success btn-xs active");
        else if ($(this).attr("rel") == "check")
            $(this).attr("rel", "uncheck").removeClass("btn btn-success btn-xs active").addClass("btn btn-danger btn-xs");
    });

    tpl_done();

    $.extend({alertMsg: function(message, title, h, w) {

            h = h || 250;
            w = w || 300;


            $("<div></div>").dialog({
                buttons: {"Ok": function() {
                        $(this).dialog("close");
                    }},
                close: function(event, ui) {
                    $(this).remove();
                },
                stack: false,
                resizable: false,
                title: title,
                modal: true,
                height: h,
                width: w,
            }).html(message);
        }
    });

    $.extend({confirm: function(msg, calback) {

            $("<div></div>").dialog({
                buttons: {
                    "Ok": function() {
                        eval(calback)();
                        $(this).remove();
                    },
                    "Annuler": function() {
                        $(this).remove();
                    }
                },
                close: function(event, ui) {
                    $(this).remove();
                },
                resizable: false,
                title: "Message de confirmation",
                modal: true
            }).text(msg);
        }
    });

    $.extend({confirmhtml: function(msg, calback) {

            $("<div></div>").dialog({
                buttons: {
                    "Ok": function() {
                        eval(calback)();
                        $(this).remove();
                    },
                    "Annuler": function() {
                        $(this).remove();
                    }
                },
                close: function(event, ui) {
                    $(this).remove();
                },
                resizable: false,
                title: "Message de confirmation",
                modal: true
            }).html(msg);
        }
    });

    $.extend({getServerMessage: function(data) {
            if (data) {
                if (data["etat"] == "2") {
                    get_ajax_data("/main/asyn_get_exception", param = {}, "ExceptionDialog");
                }
                if (data["etat"] == "1") {
                    get_ajax_data("/main/asyn_get_msg", param = {}, "MsgDialog");
                }
                if (data["etat"] == "3") {
                    get_ajax_data("/main/asyn_get_exception", param = {}, "ExceptionDialog");
                    get_ajax_data("/main/asyn_get_msg", param = {}, "MsgDialog");
                }
            }
        }
    });

    $.extend({dlg: function(id, idinsert, title, btn, titreentet, h, w, closefcnt) {

            w = w || $(window).width() * 0.8;
            h = h || $(window).height() * 0.8;

            $("#" + id).dialog({
                position: 'center',
                draggable: false,
                title: title,
                resizable: false,
                dialogClass: 'dialogRecurso',
                width: w,
                height: h,
                zIndex: 9999,
                autoOpen: false,
                modal: true,
                buttons: btn,
                close: function(event, ui) {
                    $("#qter").tipsy("hide").removeAttr('original-title');
                    $("#" + id).html("");
                    //closefcnt();
                }
            }).html('<div class="panel panel-default pangidag">\n\
                    <div class="panel-heading"> <strong>' + titreentet + '</strong></div>\n\
                    <div class="panel-body diagbody" id="' + idinsert + '">' + $("#" + id).html() + '</div></div>');



        }

    })

    $.extend({typinfo: function(msg) {

            $(".ajaxspin").attr("title", msg);

            $(".ajaxspin").tipsy({
                fade: false,
                gravity: 's',
                className: "tipsyinfo"
            });

            $(".ajaxspin").tipsy("show");

            $(document).on('scroll', function() {
                $(".ajaxspin").tipsy("show");
            });

            $(".ajaxspin").on("hide", function() {
                $(".ajaxspin").tipsy("hide");
            })
        }
    });





    $.extend($.tablesorter.themes.bootstrap, {
        // these classes are added to the table. To see other table classes available,
        // look here: http://twitter.github.com/bootstrap/base-css.html#tables
        table: 'table table-bordered',
        caption: 'caption',
        header: 'bootstrap-header', // give the header a gradient background
        footerRow: '',
        footerCells: '',
        icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
        sortNone: 'bootstrap-icon-unsorted',
        sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up', // includes classes for Bootstrap v2 & v3
        sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
        active: '', // applied when column is sorted
        hover: '', // use custom css here - bootstrap class may not override it
        filterRow: '', // filter row class
        even: '', // odd row zebra striping
        odd: ''  // even row zebra striping
    });

    $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Merci de verifier la valeur du champ"
            );

    jQuery.validator.addMethod("greaterThanDate",
            function(value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) >= new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val())
                        || (Number(value) > Number($(params).val()));
            }, 'Doit être > = date reception .');

    jQuery.validator.addMethod("smallerThanDate",
            function(value, element, params) {
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) < new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val())
                        || (Number(value) < Number($(params).val()));
            }, 'Doit être < = date premiére circulation .');



});

function ExceptionDialog(data) {

    if (data.length != 0) {

        var errHtml = "<ul>";
        $.each(data, function(item, text) {
            errHtml += "<li>" + text + "</li>";
        })
        errHtml += "</ul>";
        $("<div></div>").dialog({
            buttons: {"Ok": function() {
                    $(this).dialog("close");
                }},
            close: function(event, ui) {
                $(this).remove();
            },
            resizable: false,
            title: "Erreur",
            modal: true
        }).html(errHtml);
    }

}

function MsgDialog(data) {

    if (data.length != 0) {

        var errHtml = "<ul>";
        $.each(data, function(item, text) {
            errHtml += "<li>" + text + "</li>";
        })
        errHtml += "</ul>";
        $("<div></div>").dialog({
            buttons: {"Ok": function() {
                    $(this).dialog("close");
                }},
            close: function(event, ui) {
                $(this).remove();
            },
            resizable: false,
            title: "System",
            modal: true
        }).html(errHtml);
    }

}
