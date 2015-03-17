<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return


    function ($script = null){
        //TODO

        if(is_array($script) ){
        if(isset($script[0]))
            $script = $script[0];
        if(count($script) == 0)
            $script = null;
    }

    /** @var View $this */
    $_this = \Core\Helper\Registry::getInstance()->view;

    if (app()->hasLayout()) {
        // it's stack for <head>
        $view = app()->getLayout();

        $headScripts = $view->system('headScripts') ? : array();



        if (null === $script) {
            $headScripts = array_unique($headScripts);
            // clear system vars
            $view->system('headScripts', array());

            $headScripts = array_map(array($_this, 'script'), $headScripts);
            return join("\n", $headScripts);
        } else {
            $headScripts[] = $script;
            $view->system('headScripts', $headScripts);
        }
    } else {
        // it's just alias to script() call
        return $_this->script($script);
    }

    };
    
   