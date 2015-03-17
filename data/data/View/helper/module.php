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
    function ($module = null) {
    /** @var View $this */
    //$_this = \Core\Helper\Registry::getInstance()->view;


    
    $request = app()->getRequest();
    if (null == $module) {
        return $request->getModule();
    } else {
        return $request->getModule() == $module;
    }
    };