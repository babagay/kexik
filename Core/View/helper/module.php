<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 09.04.14
 * Time: 13:03
 */
namespace Core\View\Helper;


return
    /**
     * Return module name
     * or check to current module
     *
     * @param string $module
     * @return string|boolean
     */
    function ($module = null, $controller = null) {
    /** @var View $this */
    //$_this = \Core\Helper\Registry::getInstance()->view;


    
    $request = app()->getRequest();


    if (null == $module) {
        return $request->getModule();
    } else {
        if(null === $controller)
            return $request->getModule() == $module;
        else {
            if($request->getModule() == $module
            AND $request->getController() == $controller)
                return true;
            else
                return false;
        }
    }
    };