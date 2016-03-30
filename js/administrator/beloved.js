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
            $("#graveyard_search").keyup(function(){
                $("#graveyard_search ~ img").show();
                $.get(
                    $(this).data("url"),
                    {
                        sSearch:$(this).val()
                    },
                    function(data){
                        $("#graveyard_search_list").html(data);
                        $("#graveyard_search ~ img").hide();
                        graveyardListManagement();
                    }
                );
            });
            $("#parlour_search").keyup(function(){
                $("#parlour_search ~ img").show();
                $.get(
                    $(this).data("url"),
                    {
                        sSearch:$(this).val()
                    },
                    function(data){
                        $("#parlour_search_list").html(data);
                        $("#parlour_search ~ img").hide();
                        parlourListManagement();
                    }
                );
            });
            $("#beloving_search").keyup(function(){
                $("#beloving_search ~ img").show();
                $.get(
                    $(this).data("url"),
                    {
                        sSearch:$(this).val()
                    },
                    function(data){
                        $("#beloving_search_list").html(data);
                        $("#beloving_search ~ img").hide();
                        belovedListManagement();
                    }
                );
            });
            $("#memento_item_search").keyup(function(){
                $("#memento_item_search ~ img").show();
                $.get(
                    $(this).data("url"),
                    {
                        sSearch:$(this).val()
                    },
                    function(data){
                        $("#memento_item_search_list").html(data);
                        $("#memento_item_search ~ img").hide();
                        mementoItemListManagement();
                    }
                );
            });
            graveyardListManagement();
            parlourListManagement();
            belovedListManagement();
            mementoItemListManagement();
        }
        var graveyardListManagement = function() {
            $(".addGraveyard").click(function (e) {
                var el = $(this).closest(".row").detach();
                var id = el.find(".actions.add").data("id");
                el.find(".actions.add").removeClass("add").addClass("removedGraveyard").addClass("delete").after("<input type='hidden' name='graveyard[]' value='"+id+"'/>");
                $("#graveyard_list").append(el);
                graveyardListManagement();
                e.preventDefault();
            });
            $(".removedGraveyard").click(function (e) {
                $(this).closest(".row").empty();
                graveyardListManagement();
                e.preventDefault();
            }); 
            
        }
        var parlourListManagement = function() {
            $(".addParlour").click(function (e) {
                var el = $(this).closest(".row").detach();
                var id = el.find(".actions.add").data("id");
                el.find(".actions.add").removeClass("add").addClass("removedParlour").addClass("delete").after("<input type='hidden' name='parlour[]' value='"+id+"'/>");
                $("#parlour_list").append(el);
                parlourListManagement();
                e.preventDefault();
            });
            $(".removedParlour").click(function (e) {
                $(this).closest(".row").empty();
                parlourListManagement();
                e.preventDefault();
            }); 
            
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
        var mementoItemListManagement = function() {
            $(".addMementoItem").click(function (e) {
                e.preventDefault();
                if ($(this).closest(".row").find("input").val() == "") {
                   $(".missing_memento_data").dialog({
                    buttons: [
                      {
                        text: "Ok",
                        click: function() {
                          $( this ).dialog( "close" );
                        }
                      }
                    ]});
                  return;
                }
                var el = $(this).closest(".row").detach();
                var code = el.find(".actions.add").data("code");
                el.find(".actions.add").removeClass("add").addClass("removedMementoItem").addClass("delete").after("<input type='hidden' name='memento_item_code[]' value='"+code+"'/>");                
                $("#memento_item_list").append(el);
                mementoItemListManagement();
            });
            $(".removedMementoItem").click(function (e) {
                $(this).closest(".row").empty();
                mementoItemListManagement();
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