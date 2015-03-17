<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 18:15
 */
namespace Core\View\Helper;


return
    /**
     * dispatch
     *
     * <code>
     * $this->dispatch($module, $controller, array $params);
     * </code>
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return View|null
     */

    function ($module, $controller = null, $params = array()) {
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
        }

        /*
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


       */
        if($mdl !== false){
            $module = $mdl;
        }

        // --

        $application = app();
        try {
            //fb($module);
            //fb($controller);
            //fb($params);
            $view = $application->dispatch($module, $controller, $params);
        } catch (AclException $e) {
            // nothing for Acl exception
            return null;
        } catch (\Exception $e) {
            if (app()->isDebug()) {
                // exception message for developers
                return
                    '<div class="alert alert-error">' .
                    '<strong>Dispatch of "' . $module . '/' . $controller . '"</strong>: ' .
                    $e->getMessage() .
                    '</div>';
            } else {
                // nothing for production
                return null;
            }
        }

        // run closure
        if ($view instanceof \Closure) {
            return $view();
        }
        return $view;

    };