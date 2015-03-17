<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return


    function ($style = null, $media = 'all') {

        $_this = \Core\Helper\Registry::getInstance()->view; // Bluz\View\View
        // аналогично app()->getView()

        $twig_view = \Core\Helper\Registry::getInstance()->twig_view;

        $tmp = false;
        if(is_array($style) AND count($style) == 0)$style = null;

        if(is_array($style)){
            if(isset($style[0]))
                $tmp = $style[0];
            if(isset($style[1]))
                $media = $style[1];
        }
        if($tmp) $style = $tmp;


    /** @var View $this */
    if (app()->hasLayout()) {
        // it's stack for <head>
        $view = app()->getLayout();

        $headStyle = $view->system('headStyle') ? : array();

        if (null === $style) {
            // clear system vars
            $view->system('headStyle', array());

            array_walk(
                $headStyle,
                function (&$item, $key) use ($twig_view){
                    $item = $twig_view->style($key, $item);
                }
            );
            return join("\n", $headStyle);
        } else {
            $headStyle[$style] = $media;
            $view->system('headStyle', $headStyle);
        }
    } else {

        // it's just alias to script() call
        return $twig_view->style($style);
    }
    };