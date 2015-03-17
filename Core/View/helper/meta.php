<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return


   function ($name = null, $content = null) {





    /** @var View $this */
    if (app()->hasLayout()) {
        // it's stack for <head>
        $layout = app()->getLayout();

        $meta = $layout->system('meta') ? : array();

        if ($name && $content) {
            $meta[] = array('name' => $name, 'content' => $content);
            $layout->system('meta', $meta);
        } elseif (is_array($name)) {
            $meta[] = $name;
            $layout->system('meta', $meta);
        } elseif (!$name && !$content) {
            if (sizeof($meta)) {
                // prepare to output
                $meta = array_map(
                    function ($attr) {
                        return '<meta '. $this->attributes($attr) .'/>';
                    },
                    $meta
                );
                // clear system vars
                $layout->system('meta', array());
                return join("\n", $meta);
            } else {
                return '';
            }
        }
    }
    return '';
    };