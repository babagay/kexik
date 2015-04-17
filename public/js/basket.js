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


        }
    };

    // on DOM ready state
    $(function(){

    });

    return basket;
});
