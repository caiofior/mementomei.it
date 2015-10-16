$(document).ready(function() {
    
    Beloving = (function () {
        var Beloving = function () {
            
            var instantiateTable = function() {
            var el = $("#graveyardT");    
            var ct = el.dataTable({
                "oLanguage":  {
                    "sUrl": "js/common/datatables/lang/it.json"
                 },
                "bStateSave" : true,
                "aaSorting": [[ 1, "desc" ]],
                "bJQueryUI": true,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "#",
                 "fnServerParams": function ( aoData ) {
                    aoData.push({ "name": "task", "value": "graveyard" });  
                 },
                "aoColumnDefs":  getDatatableMetadata(el),
                "drawCallback": function( ) {
                 $(".actions.delete").click(function (e) {
                    e.preventDefault();
                    $(this).dialog({
                       buttons: {
                          "Confermi la cancellazione del cimitero?": function() {
                             $.ajax({
                                url: $(this).attr("href"),
                                async : false
                                });
                             ct.dataTable().fnDraw();
                             $( this ).dialog( "close" );
                          }
                       }
                    });
                 });
                }
            });
        }
        var instantiateForm = function() {
            tinymce.init({
              force_br_newlines : false,
              force_p_newlines : false,
              forced_root_block : "",
              entity_encoding : "raw",  
              plugins: "link",
              selector: "textarea"
            });
            $("#first_name,#last_name").change(function(){
                var oldvalue=$("#first_name").data("oldvalue")+" "+$("#last_name").data("oldvalue");
                var currentvalue = $("#description").val();
                if (currentvalue == "" || oldvalue == currentvalue) {
                    $("#description").val($("#first_name").val()+" "+$("#last_name").val());
                    $("#first_name").data("oldvalue",$("#first_name").val());
                    $("#last_name").data("oldvalue",$("#last_name").val());        
                }
            });
            $.datepicker.setDefaults(
                $.extend(
                    $.datepicker.regional[ "it" ],
                    {showOn: "button"},
                    {buttonImage: "style/general/images/calendar.gif"},
                    {buttonImageOnly: true},
                    {'dateFormat':$.datepicker.W3C}
                )
            );
            $("input[type=date]").datepicker();
        }    
        instantiateTable();
        instantiateForm();
        };
        return Beloving;
    })();
    
    beloving = new Beloving();
    
});