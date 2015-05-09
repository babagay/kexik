/**
 * require(["filters"], function($, notify) {});
 */
define(['jquery','bluz'], function ($,bluz) {

    "use strict";

    var filters = {
        name: "filters",
        getName: function() {
            return this.name
        },
        test: function () {
        }
    };

    // on DOM ready state
    $(function(){

        // код ниже не работает по непонятным причинам, связанным с возвращением неверного значение функцией checkbox.prop("checked")
        // или, скорее всего, с тем, что эта функция что-то ломает при изменении свойства, напр: checkbox.prop("checked",true)
      $("input[type=checkbox].filter-origin").off().on("click",function(event, data, textStatus, jqXHR){

            var setChecked = false
            var checkbox =  $("form[name=state] input[name=filter-origin]");
            var data_id = $(this).attr("data-id")
            var filter_origin = $("form[name=state] input[name=filter-origin]").val();

          console.log(checkbox.prop("checked"))
        return true

            var Re  = new RegExp(   data_id  );
            var Re1 = new RegExp( "^" + data_id + ",");
            var Re2 = new RegExp( "," + data_id + "$");
            var Re3 = new RegExp( "," + data_id + "," );

            //var filter_subcategory = $("form[name=state] input.filter-subcategory").val();

            if(filter_origin == ""){
                if( checkbox.prop("checked") ){
                    filter_origin = $(this).attr("data-id")
                    $("form[name=state] input[name=filter-origin]").val(filter_origin);
                    setChecked = true
                } else {
                    console.log(1)
                    return false;
                }

            } else {
                console.log("Пишем")
                console.log(checkbox.prop("checked"))
                if( checkbox.prop("checked") ){
                    if( Re1.test(filter_origin) || Re2.test(filter_origin) || Re3.test(filter_origin) ){
                        // "не дописывать, т.к. уже в списке
                        $(".filter-origin").prop("checked",true) // FIXME не выставляет галочку
                        console.log(2)
                        return false
                    } else {
                        // дописать id, если его не было в списке
                        console.log("дописать")

                        filter_origin = filter_origin + "," + data_id
                        $("form[name=state] input[name=filter-origin]").val(filter_origin);

                        setChecked = true
                    }
                } else {
                    if( Re.test(filter_origin) ){
                        // убрать id, если он в списке
                        if(filter_origin == $(this).attr("data-id")){
                            $("form[name=state] input[name=filter-origin]").val("")
                        } else {

                            //Re = new RegExp( "^" + data_id + ",");
                            if( Re1.test(filter_origin) ){ // в начале строки
                                filter_origin = filter_origin.replace(Re1,"")
                            } else {
                                // Re = new RegExp( "," + data_id + "$")
                                if( Re2.test(filter_origin) ) { // в конце строки
                                    filter_origin = filter_origin.replace(Re2,"")
                                } else {
                                    // Re = new RegExp( "," + data_id + "," )
                                    if( Re3.test(filter_origin) )  // в середине
                                        filter_origin = filter_origin.replace(Re3,",")
                                }
                            }

                            if( filter_origin.substr(-1) == "," )
                                filter_origin = filter_origin.replace(/,$/,"")

                            $("form[name=state] input[name=filter-origin]").val(filter_origin)
                        }

                        setChecked = false
                    } else {
                        // не в списке
                    }
                }

                //filter_origin = filter_origin + "," + $(this).attr("data-id")
            }

            //$("form[name=state] input[name=filter-origin]").val(filter_origin);


            var data = {}
            var state = $("form[name=state]").serialize();
            var is_search = $("input[name=is_search]").val()
            var url = "catalog/products" + "?" + state

            if(is_search == 1){
                url = "поиск/" + $("input[name=search_text]").val() + "?" + state;
                //var manufacturers_id = $("form[name=state] input[name=filter-manufacturers_id]").val()
                //data["manufacturers_id"] = manufacturers_id.substring(3)
            }

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                context: document.body,
                dataType: "html"
            }).done(function(response,status,responseObj) {
                    if(status == "success"){
                        if(responseObj.status != 200){
                            notify.addError("Error 404")
                        }

                        $("#content_box").html(response)

                        $(".filter-origin").prop("checked",setChecked)
                        //$(checkbox).prop("checked",setChecked)
                    }
                });
          console.log(3)
          return false;
        });

        $("input[type=checkbox].filter-subcategory").off().on("click",function(event, data, textStatus, jqXHR){

        });
    });

    return filters;
});
