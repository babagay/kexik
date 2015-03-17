<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


use Core\Helper\Registry;

return


    function ($file = null) {

        if (is_array($file)) {
            if (isset($file[0]))
                $file = $file[0];
            if (count($file) == 0)
                $file = null;
        }

        /** @var View $this */
        // setup baseUrl

        //TODO сравнить, что возвращают эти три варианта
        //$_this = \Bluz\View\View::getInstance();
        //$_this = app()->getInstance()->view;     //getView();
        $_this = \Core\Helper\Registry::getInstance()->view;

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