/**
 * Grid Spy Handler ^_^
 *
 * @author   Anton Shevchuk
 * @created  11.09.12 10:30
 */
/*global define,require*/
define(['jquery', 'bluz'], function ($, bluz) {
    "use strict";
    $(function () {
        $('[data-spy="grid"]').each(function (i, el) {
            var $grid = $(el);
            // TODO: work with hash from URI
            if (!$grid.data('url')) {
                //console.log($grid.data( ))
                // Если не задан гридовый урл, им становится текущий урл страницы
                $grid.data('url', window.location.pathname);
            }

            $grid.refresh = function(){
                $.ajax({
                    url: $grid.data('url'),
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
            }

            // Необходимо сделать рефреш
            $grid.bind("refresh");

            $grid.bind("reload", function() {
                // ГРида перегрузилась
                // console.log("reload fired")
                // console.log($grid.data('grid')) // атрибут data-grid гриды, которая перегрузилась
            });

            $grid.bind("item-added", function() {
                // Добавлен новый продукт
            });

            $grid.bind("item-updated", function() {
                // Изменен айтем
            });

            // Drop item
            $grid.on('click.bluz.ajax','a.drop-item',  function () {
                //console.log($grid.data('url')) // Выводит текущий урл, напр /kex/orders/edit/orders_id/89
                //console.log( $(this).data('ajax-type') ) Берет тип ожидаемых данных (напр html) из атрибута data-ajax-type
                //console.log( $(this).data('ajax-method') ) Берет http-метод, напр, get

                var id = $(this).attr("data-id")
                var href = $(this).attr('href');

                $.ajax({
                    url: href,
                    type: 'delete',
                    dataType: 'html',
                    success: function (html) {
                        //$grid.data('url', href);
                        $grid.html($(html).children().unwrap());

                        $grid.trigger("reload");
                    }
                });
                return false;
            });

            // Add item
            $grid.on('click.bluz.ajax', 'a.add-item', function () {
                /**
                 * console.log($grid.data('url')) Выводит текущий урл, напр /kex/orders/edit/orders_id/89
                 *
                 * @type {*|jQuery}
                 */
                var id = $(this).attr("data-id")
                var href = $(this).attr('href');
                var extrafield_name = $(this).data('extrafield-name')
                var refreshItem = $(this).data('refresh')
                var extrafield_value = undefined

                if(extrafield_name != undefined) {
                    extrafield_value = $("input[name="+extrafield_name+"]").val()
                }

                if(extrafield_value != undefined)
                    href += "/" + extrafield_name + "/" + extrafield_value



                $.ajax({
                    url: href,
                    type: 'add',
                    dataType: 'html',
                    success: function (html) {
                        //$grid.data('url', href);
                        $grid.html($(html).children().unwrap());

                        $grid.trigger("item-added");
                        $grid.trigger("reload");


                    }
                });







                    /*.then(

                        function(){

                            var $grid = $("div[name="+ refreshItem +"]")

                            // Обновить вторую гриду
                            if( $grid.data('url') != undefined )
                                $.ajax({
                                    url: $grid.data('url'),
                                    type: 'get',
                                    dataType: 'html',
                                    beforeSend: function () {
                                        $grid.find('a, .btn').addClass('disabled');
                                    },
                                    success: function (html) {
                                        $grid.html($(html).children().unwrap());
                                        $grid.trigger("reload");
                                    }
                                });
                        }
                    );*/

                return false;
            });

            // Update item
            $grid.on('keypress', '.update-item', function (e) {

                if(e.keyCode == 13){

                    var id = $(this).attr("data-id")
                    var url = $(this).data('url')
                    var fileld_name = $(this).data('fileld-name')
                    var data = {}
                    data["operation"] = "update"
                    data[fileld_name] = $(this).val() * 1

                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'html',
                        data: data,
                        success: function (html) {
                            $grid.html($(html).children().unwrap());

                            $grid.trigger("item-updated");
                            $grid.trigger("reload");
                        }
                    }).then(
                        function(){
                            if( data[fileld_name] == 0 ){
                                $("a.drop-item[data-id="+ id +"]").click()
                            }
                        }
                    );

                    return false;
                }
            });

            // Hide item
            $grid.on('click.bluz.ajax','a.hide-item', function () {
                var href = $(this).attr('href');

                $.ajax({
                    url: href,
                    type: 'get',
                    dataType: 'html',
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
                return false;
            });

            // pagination, sorting and filtering over AJAX
            $grid.on('click.bluz.ajax', '.pagination li a, thead a, .navbar a.ajax, a.filter', function () {
                var $link = $(this),
                    href = $link.attr('href');

                if (href === '#') {
                    return false;
                }

                $.ajax({
                    url: href,
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $link.addClass('active');
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        /*
                         need to replace only content of grid
                         current         loaded
                         <div>           <div>
                         [...]   <--     [...]
                         </div>          </div>
                         */
                        $grid.data('url', href);
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
                return false;
            });

            $grid.on('refresh', $grid.refresh);

            // refresh grid if form was success sent
            $grid.on('complete.ajax.bluz', 'a.dialog', function () {
                $.ajax({
                    url: $grid.data('url'),
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
            });

            // refresh grid if confirmed ajax button (e.g. delete record)
            $grid.on('success.ajax.bluz', 'a.ajax.confirm', function () {
                $.ajax({
                    url: $grid.data('url'),
                    type: 'get',
                    dataType: 'html',
                    beforeSend: function () {
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
            });


            // apply filter form
            $grid.on('submit.bluz.ajax', 'form.filter-form', function () {
                var $form = $(this);

                // magic like
                if ($form.find('[name=search-like]').length) {
                    $form.find('.grid-filter-search-input').val('like-' + $form.find('[name=search-like]').val());
                }

                $.ajax({
                    url: $form.attr('action') + '/',
                    type: 'get',
                    data: $form.serializeArray(),
                    dataType: 'html',
                    beforeSend: function () {
                        $form.addClass('disabled');
                        $grid.find('a, .btn').addClass('disabled');
                    },
                    success: function (html) {
                        $grid.html($(html).children().unwrap());
                        $grid.trigger("reload");
                    }
                });
                return false;
            });

            // magic control for like plugin
            $grid.on('click.bluz.grid', '.grid-filter-search a', function (e) {
                var $a = $(this);
                $grid.find('.grid-filter-search .dropdown-toggle').html($a.text() + ' <span class="caret"></span>');
                $grid.find('.grid-filter-search-input').attr('name', $a.data('filter'));

                e.preventDefault();
            });

            $grid.reload = function(){
                console.log(12)
            };

        });

    });
    return {};
});