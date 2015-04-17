/**
     require(["basket"], function(basket) {
          console.log( basket.trim("Hello World", "Hdle") );
     });

 */
define(['jquery','bluz'], function ($,bluz) {

    "use strict";

    var basket = {
        name: "Basket",
        getName: function() {
            return this.name
        },
        recalcProductsInBasket: function (products_id) {
            // Проверить корзину и увеличить счетчик товаров, если такого продукта в ней не было
        },
        redrawCabinetWg: function (delay) {
            // TODO можно перенести этот код в модуль Виджет, например

            var data = {}

            delay *= 1000

            setTimeout(function(){
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "users/widget/cabinet",
                    context: document.body,
                    dataType: "html"
                }).done(function(response,status,responseObj) {
                        if(status == "success"){
                            $(".widget.wg-cabinet").html(response)
                        }
                    });
            },delay)
        },
        clearBasket: function() {
            var data = {}
            var url = "basket/ajax/modify_basket"

            data["mode"] = "clear"

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                context: document.body,
                dataType: "json"
            }).done(function(response,status,responseObj) {
                    $( this ).addClass( "done" );
                    if(status == "success"){

                        if(response.response == "ok"){
                            ///console.log("ок")
                            //$("#basket-items").html("")

                            var data = {}
                            data["step"] = 1

                            $.ajax({
                                type: "POST",
                                data: data,
                                url: "корзина",
                                context: document.body,
                                dataType: "html"
                            }).done(function(response,status,responseObj) {
                                    $( this ).addClass( "done" );
                                    if(status == "success"){
                                        $("#content_box").html(response)

                                        if(responseObj.status != 200){
                                            // notify.addError("Error 404")
                                        }
                                    }
                                });

                        } else {
                            // console.log("Ошибка данных")
                        }


                        if(responseObj.status != 200){
                            // notify.addError("Error 404")
                        }
                    }

                    basket.redrawCabinetWg(2);
                });
        }
    };

    // on DOM ready state
    $(function(){

    });

    return basket;
});
