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
     * @param string|array|null $name
     * @param string|null $content
     * @return string
     */
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
