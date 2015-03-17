<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 18:12
 */
namespace Core\View\Helper;


return
    /**
     * API call from View
     * Be carefully, use it for calculate/update/save some data
     * For render information use Widgets!
     *
     * <pre>
     * <code>
     * $this->api($module, $method, array $params);
     * </code>
     * </pre>
     *
     * @param string $module
     * @param string $method
     * @param array $params
     * @return View
     */

    function ($module, $method = null, $params = array()) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;


        // Bug с параметрами
        $mdl = false;
        if(is_array($module)){
            if(isset($module[0])){
                $mdl = $module[0];
            }
            if(isset($module[1])){
                $method = $module[1];
            }
            if(isset($module[2])){
                $params = $module[2];
            }
        }

        if($mdl !== false){
            $module = $mdl;
        }

        /*

        //fb($text);




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



        // --

        $app = app();

        */

        $application = app();
        try {
            $apiClosure = $application->api($module, $method);
            return call_user_func_array($apiClosure, $params);
        } catch (\Exception $e) {
            if (app()->isDebug()) {
                // exception message for developers
                echo
                    '<div class="alert alert-error">' .
                    '<strong>API "' . $module . '/' . $method . '"</strong>: ' .
                    $e->getMessage() .
                    '</div>';
            }
        }

    };