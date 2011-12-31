<?php
/*
 * тест
 */
namespace Core;

/*
    пример $a = new Core\View();
    или $a = new \Core\View();

    Сейчас это работате благодаря строке
    "$loader->setUseIncludePath(true);"
    в ComposerAutoloaderInit24bd18a258016df6e2226afa1d747edd:: getLoader()
*/

class View {

    function __construct(){
        fb(34);
    }

}