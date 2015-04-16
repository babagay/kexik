/**
 * Bluz super global
 * @author   Anton Shevchuk
 * @created  11.09.12 10:02
 */
/*global define,require*/
define(['jquery', 'bootstrap'], function ($) {
	"use strict";

    var bluz = {
        log: function (error, text) {
            if (window.console !== undefined) {
                window.console.error(error, "Response Text:", text);
                //console.log("Error")
            }
        },
        trim: function (str, charlist ) {
            // console.log( bluz.trim("Hello World", "Hdle") );
            charlist = !charlist ? ' \\s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
            var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
            return str.replace(re, '');
        }
    };

    $(function(){
        // TODO: require other modules if needed
        if ($.fn.tooltip) {
            $('.bluz-tooltip').tooltip();
        }

        if ($.fn.affix) {
            $('.bluz-affix').affix();
        }

        // remove FB API's anchor #_=_
        if (window.location.hash === '#_=_') {
            window.location.hash = '';
            history.pushState('', document.title, window.location.pathname);
        }
    });

    return bluz;
});

/*
 function trim( str, charlist ) {    // Strip whitespace (or other characters) from the beginning and end of a string



 // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 // +   improved by: mdsjack (http://www.mdsjack.bo.it)
 // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
 // +      input by: Erkekjetter
 // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)



 charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');

 var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');

 return str.replace(re, '');

 }
 */