<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Core\View\Helper;

use Bluz\View\View;

return
    /**
     * @param string $style
     * @param string $media
     * @return string|View
     */
    function ($style, $media = 'all') {
        /** @var View $this */

        $_this = \Core\Helper\Registry::getInstance()->view;

        $tmp = false;
 
        // Хак с параметрами
        if(is_array($style)){ 
            if(isset($style[0]) OR $style[0] === null){ 
                $tmp = $style[0];
            }
            if(isset($style[1])){
                $media = $style[1];
            }
        }

        if($tmp !== false){
            $style = $tmp;
        }

        if($style === null) return false;


        if ('.css' == substr($style, -4)) {
            if (strpos($style, 'http://') !== 0
                && strpos($style, 'https://') !== 0
            ) {
                $style = $_this->baseUrl($style);
            }
            return "\t<link href=\"" . $style . "\" rel=\"stylesheet\" media=\"" . $media . "\"/>\n";
        } else {
            return "\t<style type=\"text/css\" media=\"" . $media . "\">\n"
            . $style . "\n"
            . "\t</style>";
        }
    };