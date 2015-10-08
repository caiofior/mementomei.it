$(document).ready(function() {
    
    Beloving = (function () {
        var Beloving = function () {
            
            var instantiateTable = function() {
            var el = $("#belovedT");    
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
                    aoData.push({ "name": "task", "value": "beloved" });  
                 },
                "aoColumnDefs":  getDatatableMetadata(el),
                "drawCallback": function( ) {
                 $(".actions.delete").click(function (e) {
                    e.preventDefault();
                    $(this).dialog({
                       buttons: {
                          "Confermi la cancellazione del deceduto?": function() {
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
            $("#beloving_search").keyup(function(){
                $("#beloving_sprite").show();
                $.get(
                    $(this).data("url"),
                    {
                        sSearch:$(this).val()
                    },
                    function(data){
                        $("#beloving_search_list").html(data);
                        $("#beloving_sprite").hide();
                        belovedListManagement();
                    }
                );
            });
            belovedListManagement();
        }    
        var belovedListManagement = function() {
            $(".addBeloved").click(function (e) {
                var el = $(this).closest(".row").detach();
                var id = el.find(".actions.add").data("id");
                el.find(".actions.add").removeClass("add").addClass("removedBeloved").addClass("delete").after("<input type='hidden' name='beloving[]' value='"+id+"'/>");
                $("#beloving_list").append(el);
                belovedListManagement();
                e.preventDefault();
            });
            $(".removedBeloved").click(function (e) {
                $(this).closest(".row").empty();
                belovedListManagement();
                e.preventDefault();
            }); 
        }
        instantiateTable();
        instantiateForm();
        };
        return Beloving;
    })();
    
    beloving = new Beloving();
    
});