<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 29.04.14
 * Time: 13:19
 */
namespace Core\View\Helper;

use Bluz\Application\Application;
use Bluz\View\View;
use Bluz\View\ViewException;

return
    /**
     * partial
     *
     * be careful, method rewrites the View variables with params
     *
     * @param string $__template
     * @param array $__params
     * @throws ViewException
     * @return string
     */
    function ($__template, $__params = array()) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;



        // Bug с параметрами
        $txt = false;

        if(is_array($__template)){
            if(isset($__template[0])){
                $txt = $__template[0];
            }
            if(isset($__template[1])){
                $__params = $__template[1];
            }
        }

        if($txt !== false){
            $__template = $txt;
        }
        // Bug с параметрами end

        $path = PATH_VIEW ;

        return $result = $_this->TwigRender($__template,$path,$__params);



        /*
        fb( $_this->data);
        fb('path');
        fb( $_this->path);

        fb('template');
        fb( $__template);


        */

        /*
        $__file = null;
        if (file_exists($_this->path . '/' . $__template)) {
            $__file = $_this->path . '/' . $__template;
        } else {
            foreach ($_this->partialPath as $__path) {
                if (file_exists($__path . '/' . $__template)) {
                    $__file = $__path . '/' . $__template;
                    break;
                }
            }
        }
        if (!$__file) {
            throw new ViewException("Template '{$__template}' not found");
        }

        if (sizeof($__params)) {
            extract($__params);
        }
        unset($__params);

        ob_start();
        try {
            require $__file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw new ViewException("Template '{$__template}' throw exception: ".$e->getMessage());
        }
        return ob_get_clean();
        */
    };
