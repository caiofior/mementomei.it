$(document).ready(function () {

    Memento = (function () {
        var Memento = function () {

            var instantiateTable = function () {
                var el = $("#mementoT");
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
                        aoData.push({"name": "task", "value": "memento"});
                    },
                    "aoColumnDefs": getDatatableMetadata(el),
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
            }
            instantiateTable();
            instantiateForm();
        };
        return Memento;
    })();

    memento = new Memento();

});