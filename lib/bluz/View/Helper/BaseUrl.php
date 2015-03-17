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
     * baseUrl
     *
     * @param string $file
     * @return string
     */
    function ($file = null) {

        if(is_array($file) ){
            if(isset($file[0]))
                    $file = $file[0];
            if(count($file) == 0)
                $file = null;
        }

    /** @var View $this */
    // setup baseUrl
    $_this = View::getInstance();

    if (!$_this->baseUrl) {
        $_this->baseUrl = app()
            ->getRequest()
            ->getBaseUrl();
        // clean script name
        if (isset($_SERVER['SCRIPT_NAME'])
            && ($pos = strripos($_this->baseUrl, basename($_SERVER['SCRIPT_NAME']))) !== false
        ) {
            $_this->baseUrl = substr($_this->baseUrl, 0, $pos);
        }
    }

    // Remove trailing slashes
    if (null !== $file) {
        $file = ltrim($file, '/\\');
    }

    return rtrim($_this->baseUrl, '/') . '/' . $file;
    };
