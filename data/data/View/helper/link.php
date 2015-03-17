<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return


    function (array $link = null) {
    /** @var View $this */
    if (app()->hasLayout()) {
        // it's stack for <head>
        $layout = app()->getLayout();

        $links = $layout->system('link') ? : array();

        if (null === $link) {
            $links = array_unique($links);
            // prepare to output
            $links = array_map(
                function ($attr) {
                    return '<link '. $this->attributes($attr) .'/>';
                },
                $links
            );
            // clear system vars
            $layout->system('link', array());
            return join("\n", $links);
        } else {
            $links[] = $link;
            $layout->system('link', $links);
        }
    }
    return '';
    };