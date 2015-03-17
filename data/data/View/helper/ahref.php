<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return


    function ($text, $href = null, array $attributes = array()) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;

        $txt = false;

        //fb($text);

        // Bug с параметрами
        if(is_array($text)){
            if(isset($text[0])){
                $txt = $text[0];
            }
            if(isset($text[1])){
                if(isset($text[1][0])) $href = $text[1][0];
                if(isset($text[1][1])){
                    if(count($text[1][1]) == 1)
                        $attributes = array(0 => $text[1][1]);
                    else
                        $attributes = $text[1][1];

                }
            }
        }

        if($txt !== false){
            $text = $txt;
        }




        // if href is settings for url helper
        if (is_array($href)) {
            $href = call_user_func_array(array($_this, 'url'), $href);
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
        //fb($attributes);
        $attributes = $_this->attributes($attributes);

        //fb($attributes);

        return '<a href="' . $href . '" ' . $attributes . '>' . __($text) . '</a>';
    };