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
     * @author ErgallM
     *
     * @param string $text
     * @param string|array $href
     * @param array $attributes HTML attributes
     * @return \Closure
     */
    function ($text, $href = null, array $attributes = array()) {
        /** @var View $this */
        // if href is settings for url helper

        $tmp = null;

        if(is_array($text)){
            if(isset($text[0]))  $tmp = $text[0];
            if(isset($text[1]))  $href = $text[1];
            if(isset($text[2]))  $attributes = $text[2];
        }
        if(!is_null($tmp)) $text = $tmp;


        if (is_array($href)) {
            $href = call_user_func_array(array($this, 'url'), $href);
        }

        // href can be null, if access is denied
        if (null === $href) {
            return '';
        }

        if ($href == app()->getRequest()->getRequestUri()) {
            if (isset($attributes['class'])) {
                $attributes['class'] .= ' on';
            } else {
                $attributes['class'] = 'on';
            }
        }

        $attributes = $this->attributes($attributes);

        return '<a href="' . $href . '" ' . $attributes . '>' . __($text) . '</a>';
    };
