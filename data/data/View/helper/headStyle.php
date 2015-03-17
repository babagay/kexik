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

        if(is_array($style)){
            if(isset($style[0]))
                $style = $style[0];
            if(count($style) == 0)
                $style = null;
        }



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
                function (&$item, $key) {
                    $item = $this->style($key, $item);
                }
            );
            return join("\n", $headStyle);
        } else {
            $headStyle[$style] = $media;
            $view->system('headStyle', $headStyle);
        }
    } else {
        // it's just alias to script() call
        return $this->style($style);
    }
    };