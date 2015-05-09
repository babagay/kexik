/*global define,require*/

/**
 * <script>
 *      require(["basic" ], function(  basic  ) {});
 * </script>
 */

define(['jquery','bluz'], function ($,bluz) {
   
	"use strict";


    /**
     *
     * @type {{name: string, asd: Function, zxc: Function}}
     * Вызов: console.log(test.asd("sdc", "zxc"))
     */
    var basic = {
        name: "Test",
        asd: function(a,d) {
          return this.name
        },
        zxc: function (error, text) {
            if (window.console !== undefined) {
                window.console.error(error, "Response Text:", text);
            }
        }
    };

    // on DOM ready state
    $(function(){
        // Картинка включается, когда идет аякс-запрос
        $('#loading_2').hide();
        $('#loading_2').removeClass('hidden');

        // Подвал
        $('.content-wrapper').each(function() {
            var hhh = $('#main-nav').height()+"px";
            $(this).css("min-height",hhh);
        });

        // Обнуление состояния
        $("form[name=state] input[name=filter-origin]").val("")
        $("form[name=state] input[name=filter-subcategory]").val("")

        // test action
        $(document).on('click', 'span.jsCaller', function() {
            bluz.log('Error','Test error was thrown')
        });


        //
    });

    return basic;
      
 
  
});