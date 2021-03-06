/**
 * Configuration example
 * @author Anton Shevchuk
 */
/*global define,require*/
require.config({
    // why not simple "js"? Because IE eating our minds!
    // Для локального к пути добавляется kex
    baseUrl: './public/js',
    //baseUrl: '/kex/public/js',
    //baseUrl: '/public/js',
    // if you need disable JS cache
    urlArgs: "bust=" + (new Date()).getTime(),
    paths: {
        bootstrap: './vendor/bootstrap',
        jquery: './vendor/jquery',
        "jquery-ui": './vendor/jquery-ui',
        redactor: './../redactor/redactor',
        // jcarousel: 'jcarousel',
        // cdnjs settings
        underscore: '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.1/underscore-min',
        backbone: '//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min'
    },
    shim: {
        bootstrap: {
            deps: ['jquery'],
            exports: '$.fn.popover'
        },
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        redactor: {
            deps: ['jquery'],
            exports: '$.fn.redactor'
        },
        underscore: {
            exports: '_'
        },
        "jquery-ui": {
            deps: ['jquery'],
            exports: '$.ui'
        },
        jcarousel: {
            deps: ['jquery'],
            exports: '$.jCarousel'
        }
    },
    moment: {
        noGlobal: true
    },
    enforceDefine: true
});

require(['bluz', 'bluz.ajax', 'bootstrap', 'basic']);