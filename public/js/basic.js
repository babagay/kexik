/*global define,require*/

/**
 * <script>
 *      require(["basic" ], function(  basic  ) {});
 * </script>
 */

define(['jquery', 'bluz'], function ($, bluz) {

    "use strict";


    /**
     *
     * @type {{name: string, asd: Function, zxc: Function}}
     * Вызов: console.log(test.asd("sdc", "zxc"))
     */
    var basic = {
        name: "Test",
        modals: [],
        asd: function (a, d) {
            return this.name
        },
        zxc: function (error, text) {
            if (window.console !== undefined) {
                window.console.error(error, "Response Text:", text);
            }
        },
        round: function (value, ndec) {
            var n = 10;
            for (var i = 1; i < ndec; i++) {
                n *= 10;
            }
            if (!ndec || ndec <= 0)
                return Math.round(value);
            else
                return Math.round(value * n) / n;
        },
        createModal: function (content, style) {

            var $div = $('<div>', {'class': 'modal fade'});
            var $divDialog = $('<div>', {'class': 'modal-dialog', 'style': style});
            var $divContent = $('<div>', {'class': 'modal-content'});

            $divContent.html(content);
            $divDialog.append($divContent);
            $div.append($divDialog);
            $div.modal();

            this.modals.push($div);

            return $div;
        },
        closeModals: function () {
            for (var i = 0; i < this.modals.length; i++) {
                this.modals[i].modal('hide');
                this.modals[i].data('modal', null);
            }
            this.modals = [];
        },
        fastPopup: function(header,text,style) {
            var modal_html = "<div class=\"modal-header\">"+
                "<h3 class=\"modal-title\">"+header+"</h3>"+
                "</div>"+
                "<div class=\"modal-body\">"+
                "<p>"+text+"</p>"+
                "</div>"
            var $div = basic.createModal(modal_html, style);
            $div.modal('show');
        }
    };

    // on DOM ready state
    $(function () {
        // Картинка включается, когда идет аякс-запрос
        $('#loading_2').hide();
        $('#loading_2').removeClass('hidden');

        // Подвал
        $('.content-wrapper').each(function () {
            var hhh = $('#main-nav').height() + "px";
            $(this).css("min-height", hhh);
        });

        // Обнуление состояния
        $("form[name=state] input[name=filter-origin]").val("")
        $("form[name=state] input[name=filter-subcategory]").val("")

        // test action
        $(document).on('click', 'span.jsCaller', function () {
            bluz.log('Error', 'Test error was thrown')
        });


        //
    });

    return basic;


});