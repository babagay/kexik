<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\View\Helper;

use Bluz\View\View;

return
    /**
     * @param string $script
     * @return string|void
     */
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
