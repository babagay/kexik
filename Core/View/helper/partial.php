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

$twig_view = $this;

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
    function ($__template, $__params = array()) use($twig_view) {
        /** @var $view Bluz\View\View */
        $view = \Core\Helper\Registry::getInstance()->view;
        /** $twig_view Core\View\ZoqaTwigExtension */
        /**
         * $helpersPath = app()->getInstance()->getView()->getHelper()->getHelpersPath();
         *
         * Один раз замыкание получает на вход параметры, сваленные в массив, который попадает в $__template
         * Далее аргументы нормально разбрасываются между параметрами
         */



        $__file = null;
        $txt = false;
        $path = PATH_VIEW ;

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

        // Debug^ grid есть всегда
        // fb($__template);
        // fb($__params);
        // fb('-------------');


        $partialPath = $view->getPartialPath();

        /*
         * if (file_exists($view->path . '/' . $__template)) {
                    $__file = $view->path . '/' . $__template;
                } else
         */
        foreach ($partialPath as $__path) {
            if (file_exists($__path . '/' . $__template)) {
                $__file = $__path . '/' . $__template;
                $path = $__path;
                break;
            }
        }

        if (!$__file) {
            throw new ViewException("Template '{$__template}' not found");
        }

        /*
        // Был массив с ключом grid - появится переменная $grid
        if (sizeof($__params)) {
            extract($__params);
        }
        */

        foreach($__params as $name => $value){
            $view->$name = $value;
        }
        unset($__params);







        // FIXME: убрать переменные для гриды
        // Конкретные параметры, такие как $emptyRows, здесь лепить нельзя,
        // Т.к. это замыкание загружает любой микрошаблон, а не только для гриды
        $emptyRows = 0;
        if ($view->grid->pages() > 1 && sizeof($view->grid->getData()) < $view->grid->getLimit())
            $emptyRows = $view->grid->getLimit() - sizeof($view->grid->getData());

        $view->emptyRows = $emptyRows;

        //fb($emptyRows);

        // [!] grid есть всегда. через аджакс тоже
        // fb($view->grid);








        return $result = $view->TwigRender($__template,$path);





        /*
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
