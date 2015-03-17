<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 16:50
 */
namespace Core\View\Helper;


return


    function ($module, $controller = null, array $params = array(), $checkAccess = false) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;



        //fb($text);

        // Bug с параметрами
        $mdl = false;

        if(is_array($module)){
            if(isset($module[0])){
                $mdl = $module[0];
            }
            if(isset($module[1])){
                if(is_null($controller)) $controller =  $module[1];
            }
            if(isset($module[2])){
                if(count($params) == 0){
                    $params = $module[2];
                }
            }
            if(isset($module[3])){
                if($checkAccess === false){
                    $checkAccess = $module[3];
                }
            }
        }

        if($mdl !== false){
            $module = $mdl;
        }

        // --

        $app = app();

        try {
            if ($checkAccess) {
                $controllerFile = $app->getControllerFile($module, $controller);
                $reflectionData = $app->reflection($controllerFile);
                if (!$app->isAllowed($module, $reflectionData)) {
                    return null;
                }
            }
        } catch (\Exception $e) {
            throw new ViewException('Url View Helper: ' . $e->getMessage());
        }

        if (null === $module) {
            $module = $app->getRequest()->getModule();
        }
        if (null === $controller) {
            $controller = $app->getRequest()->getController();
        }
        if (null === $params) {
            $params = $app->getRequest()->getParams();
        }

        return $app->getRouter()
            ->url($module, $controller, $params);

    };