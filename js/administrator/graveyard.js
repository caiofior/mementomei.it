$(document).ready(function () {

    Graveyard = (function () {
        var Graveyard = function () {

            var instantiateTable = function () {
                var el = $("#graveyardT");
                var ct = el.dataTable({
                    "oLanguage": {
                        "sUrl": "js/common/datatables/lang/it.json"
                    },
                    "bStateSave": true,
                    "aaSorting": [[1, "desc"]],
                    "bJQueryUI": true,
                    "bProcessing": true,
                    "bServerSide": true,
                    "sAjaxSource": "#",
                    "fnServerParams": function (aoData) {
                        aoData.push({"name": "task", "value": "graveyard"});
                    },
                    "aoColumnDefs": getDatatableMetadata(el),
                    "drawCallback": function ( ) {
                        $(".actions.delete").click(function (e) {
                            e.preventDefault();
                            $(this).dialog({
                                buttons: {
                                    "Confermi la cancellazione del cimitero?": function () {
                                        $.ajax({
                                            url: $(this).attr("href"),
                                            async: false
                                        });
                                        ct.dataTable().fnDraw();
                                        $(this).dialog("close");
                                    }
                                }
                            });
                        });
                    }
                });
            }
            var instantiateForm = function () {
                tinymce.init({
                    force_br_newlines: false,
                    force_p_newlines: false,
                    forced_root_block: "",
                    entity_encoding: "raw",
                    plugins: "link",
                    selector: "textarea"
                });
                $( "#city" ).autocomplete({
                    source: "?task=graveyard&action=cod_istat_n",
                    select: function( e, ui ) {
                       var provinceCode = ui.item.label.match(/\([A-Z]{2}\)$/,"")[0].replace("(","").replace(")","");
                       $(this).val(ui.item.label.match(/[^\(]*/)[0].trim());
                       if (provinceCode != "" && $( "#province_code" ).val() == "") {
                           $( "#province_code" ).val(provinceCode);
                       }
                       $( "#cod_istat_n" ).val(ui.item.value);
                       e.preventDefault();
                    },
                    change: function (e, ui) {
                        if (!ui.item)
                            $(this).val("");
                    }
                }).blur(function() {                   
                    if ($("#cap").val().length != 5) {
                        $( "#cap" ).trigger("focus");
                    }
                });
                $("#cap").autocomplete({
                    source: "?task=graveyard&action=cap&city="+$("#city").val(),
                    minLength: 0,
                    select: function( e, ui ) {
                       $(this).val(ui.item.label);
                       e.preventDefault();
                    },
                    change: function (e, ui) {
                        if (!ui.item)
                            $(this).val("");
                    }
                }).bind("focus", function() {
                    if ($(this).val().length != 5) {
                        $(this).autocomplete("search");
                    }
                });
            }
            instantiateTable();
            instantiateForm();
        };
        return Graveyard;
    })();

    graveyard = new Graveyard();

});