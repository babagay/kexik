/**
 require(["basket"], function(basket) {
          console.log( basket.trim("Hello World", "Hdle") );
     });

 */
define(['jquery', 'bluz', 'basic', 'bluz.ajax'], function ($, bluz, basic, bajax) {

    "use strict";

    var basket = {
        name: "Basket",
        getName: function () {
            return this.name
        },
        recalcProductsInBasket: function (products_id) {
            // Проверить корзину и увеличить счетчик товаров, если такого продукта в ней не было
        },
        redrawCabinetWg: function (delay) {
            // TODO можно перенести этот код в модуль Виджет, например

            var data = {}

            delay *= 1

            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "users/widget/cabinet",
                    context: document.body,
                    dataType: "html"
                }).done(function (response, status, responseObj) {
                        if (status == "success") {
                            $(".widget.wg-cabinet").html(response)
                        }
                    });
            }, delay)
        },
        recalcTotal: function () {
            var total = 0;
            $(".product-item").each(function () { //display: none;

                var disp = /([\w]*:[: \w\d;]*)*(display[: ]*none[; ]*)([\w]*:[: \w\d;]*)*/i
                if (disp.test($(this).attr("style"))) {
                }
                else {
                    var products_id = $(this).find("input[name=products_id]").val()
                    var products_shoppingcart_price = $(this).find("input[name=products_shoppingcart_price]").val()
                    var products_num = $(this).find("input.num").val()
                    total += (products_shoppingcart_price * products_num)
                }
            });
            $(".basket-total").html( basic.round(total,2) )
        },
        updateProduct: function(products_id,num) {

            var data = {}
            var url = "basket/ajax/modify_basket"
            data["products_id"] = products_id
            data["mode"] = "update"
            data["products_num"] = num

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                context: document.body,
                dataType: "json"
            }).done(function (response, status, responseObj) {
                    // $(this).addClass("done");
                    if (status == "success") {
                        basket.recalcTotal()
                        basket.redrawCabinetWg(2)
                    }
                });

            return false;
        },
        clearBasket: function () {
            var data = {}
            var url = "basket/ajax/modify_basket"

            data["mode"] = "clear"

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                context: document.body,
                dataType: "json"
            }).done(function (response, status, responseObj) {
                    $(this).addClass("done");
                    if (status == "success") {

                        if (response.response == "ok") {
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
                            }).done(function (response, status, responseObj) {
                                    $(this).addClass("done");
                                    if (status == "success") {
                                        $("#content_box").html(response)

                                        if (responseObj.status != 200) {
                                            // notify.addError("Error 404")
                                        }
                                    }
                                });

                        } else {
                            // console.log("Ошибка данных")
                        }

                        if (responseObj.status != 200) {
                            // notify.addError("Error 404")
                        }
                    }

                    $(".basket-total").html("0")

                    basket.redrawCabinetWg(2);
                });
        },
        nextStep: function() {
            // TODO метод не работает
            var payment_types_id = $("input[name=payment_types_id]:checked").val()

            var next_step = $(this).attr("data-next_step");
            var step = $("input[name=step]").val() * 1

            var data = {}
            var url = "корзина"
            data["step"] = next_step

            if (step == 2) {
                var address_dostavki = $("#address_dostavki").val()
                data["address_dostavki"] = address_dostavki
                data["order_notes"] = $("#order_notes").val()
            }

            if (step == 3) {
                // Передать способ оплаты
                if(payment_types_id == undefined){
                    basic.fastPopup("Ошибка","Выберите способ оплаты","width:500px;")
                    return false;
                }
                data["payment_types_id"] = payment_types_id
            }

            $.ajax({
                type: "POST",
                data: data,
                url: url,
                context: document.body,
                dataType: "html"
            }).done(function (response, status, responseObj) {
                    $(this).addClass("done");
                    if (status == "success") {
                        $("#content_box").html(response)

                        if (responseObj.status != 200) {
                            // notify.addError("Error 404")
                        } else {
                            var data = {}
                            if (step == 3) {
                                basket.redrawCabinetWg();
                            }
                        }
                    }
                });

            return false;
        }
    };

    // on DOM ready state
    $(function () {

        // ОБработка продуктов каталога
        $('._goods').each(function (i, el) {
            var $product = $(el);

            $product.bind("added-to-basket", function() {
                // Добавлен в корзину
                basket.redrawCabinetWg(2);
            });

            $product.bind("basket-was-modified", function() {
                // Добавлен в корзину
                basket.redrawCabinetWg(2);
            });

            $product.on('click.bluz.ajax','a.push-to-basket',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var products_id = $product.find("input[name=products_id]").val();
                var products_num = $product.find("input.num").val();

                var data = {};
                data["products_id"] = products_id
                data["products_num"] = products_num
                data["mode"] = "add"

                $.ajax({
                    type: "POST",
                    data: data,
                    url: "basket/" + $(this).attr("data-href"),
                    context: document.body,
                    dataType: "json"
                }).done(function(response,status,responseObj) {
                        $( this ).addClass( "done" );
                        if(status == "success"){

                            // responseObj.responseText - is response
                            if(response.response == "ok"){

                                $product.trigger("added-to-basket")

                            } else {
                                //alert(response.response)
                                basic.fastPopup("Сообщение",response.response,'width:600px')
                                // console.log("Ошибка данных")
                            }

                            if(responseObj.status != 200){
                                // notify.addError("Error 404")
                            }

                            //$("#article-items").append(response)
                        }
                    });

                return false;

            });

            $product.on('click.bluz.ajax','a.inc',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var num = $product.find("input.num").val();
                num++

                $product.find("input.num").val(num)
                var products_shoppingcart_price = $product.find("input[name=products_shoppingcart_price]").val()

                //$product.find(".price").html( Math.round(products_shoppingcart_price * num * 100)/100 )
                $product.find(".price").html( basic.round(products_shoppingcart_price * num,2) )

                var data = {}
                var url = "basket/ajax/modify_basket"
                data["products_id"] = $product.find("input[name=products_id]").val()
                data["mode"] = "update"
                data["products_num"] = num

                $.ajax({
                    type: "POST",
                    data: data,
                    url: url,
                    context: document.body,
                    dataType: "json"
                }).done(function(response,status,responseObj) {
                        //$( this ).addClass( "done" );
                        if(status == "success"){
                            $product.trigger("basket-was-modified")
                        }
                    });

                return false;
            });

            $product.on('click.bluz.ajax','a.dec',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var num = $product.find("input.num").val();
                num--
                //if( num < 0 ) num = 0;

                if(num < 0) return false;

                $product.find("input.num").val(num)
                var products_shoppingcart_price = $product.find("input[name=products_shoppingcart_price]").val()

                $product.find(".price").html( basic.round(products_shoppingcart_price * num,2) )

                var data = {}
                var url = "basket/ajax/modify_basket"
                data["products_id"] = $product.find("input[name=products_id]").val()

                data["mode"] = "update"
                data["products_num"] = num

                $.ajax({
                    type: "POST",
                    data: data,
                    url: url,
                    context: document.body,
                    dataType: "json"
                }).done(function(response,status,responseObj) {
                        //$( this ).addClass( "done" );
                        if(status == "success"){
                            $product.trigger("basket-was-modified")
                        }
                    });

                return false;
            });

        }); // $goods end

        // ОБработка продуктов в корзине
        $('._product-item').each(function (i, el) {
            var $product_item = $(el);

            $product_item.bind("the-basket-was-modified", function() {
                basket.redrawCabinetWg(2);

                basket.recalcTotal()
            });

            // Увеличить количество единиц
            $product_item.on('click.bluz.ajax','.arrow.inc',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var num = $product_item.find("input.num").val();
                num++
                $product_item.find("input.num").val(num)
                var products_shoppingcart_price = $product_item.find("input[name=products_shoppingcart_price]").val()

                $product_item.find(".products_total").html(Math.round(products_shoppingcart_price * num * 100) / 100)

                var data = {}
                var url = "basket/ajax/modify_basket"
                data["products_id"] = $product_item.find("input[name=products_id]").val()

                data["mode"] = "update"
                data["products_num"] = num

                $.ajax({
                    type: "POST",
                    data: data,
                    url: url,
                    context: document.body,
                    dataType: "json"
                }).done(function (response, status, responseObj) {
                        $(this).addClass("done");
                        if (status == "success") {
                            $product_item.trigger("the-basket-was-modified")
                        }
                    });

                return false;
            });

            // Уменьшить количество единиц
            $product_item.on('click.bluz.ajax','.arrow.dec',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var num = $product_item.find("input.num").val();
                //num *= 1
                num--
                if (num < 0) num = 0;
                $product_item.find("input.num").val(num)
                var products_shoppingcart_price = $(this).closest(".product-item").find("input[name=products_shoppingcart_price]").val()

                $product_item.find(".products_total").html(Math.round(products_shoppingcart_price * num * 100) / 100)

                var data = {}
                var url = "basket/ajax/modify_basket"
                data["products_id"] = $product_item.find("input[name=products_id]").val()

                data["mode"] = "update"
                data["products_num"] = num

                $.ajax({
                    type: "POST",
                    data: data,
                    url: url,
                    context: document.body,
                    dataType: "json"
                }).done(function (response, status, responseObj) {
                        $(this).addClass("done");
                        if (status == "success") {
                            $product_item.trigger("the-basket-was-modified")
                        }
                    });

                return false;
            });

            // Удалить продукт
            $product_item.on('click','.remove_product',function(){
                // FIXME привязка перестает работать после перегрузки шабона корзины через аякс
                var products_id = $product_item.attr("data-products_id")

                var data = {}
                data["products_id"] = products_id
                data["mode"] = "remove"

                $.ajax({
                    type: "POST",
                    data: data,
                    url: "basket/ajax/modify_basket",
                    context: document.body,
                    dataType: "json"
                }).done(function (response, status, responseObj) {

                        if (responseObj.responseJSON.redir == "clear") {
                            basket.clearBasket();
                        } else {
                            $("#basket-items tr[data-products_id=" + products_id + "]").hide();
                            $product_item.trigger("the-basket-was-modified")
                        }
                    });
            });

        }); // product-item end

        $('.basket-controls').each(function (i, el) {

            var $control_set = $(el);

            // Очистить корзину
            /**
             * Вешается событие на элемент .clear-basket внутри контейнера $control_set
             * Если надо повесить событие на сам контейнер, нужно использовать $(this) вместо '.clear-basket'
             */
            $control_set.on('click.bluz.ajax','.clear-basket',function(){
                // FIXME привязка не работает
                // basket.clearBasket();
                // return false;
            });

            // Шаги
            $control_set.on('click.bluz.ajax','.next_step',function(){
                // FIXME отсюда не работает привязка к кнопке
                // Точнее, она работает исключительно при обычной загрузке, а через аякс не работает
                // basket.nextStep()
            });

            // Навигационные кнопки
            $control_set.on('click.bluz.ajax','.basket-btn-navi',function(){
                // FIXME привязка не работает
            });
        });



    });

    return basket;
});
