<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 16:58
 *
 * FIXME сюда заходит, но используется замыкание из Bluz/View
 */
namespace Core\View\Helper;


return


    function (array $data = array()) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;


        // Bug с параметрами
        if(isset($data[0])){
            //$data = $data[0];
        }


        if (app()->hasLayout()) {
            $layout = app()->getLayout();
            if (sizeof($data)) {
                $layout->system('breadcrumbs', $data);
            } else {
                return $layout->system('breadcrumbs');
            }
        }
        return null;

    };