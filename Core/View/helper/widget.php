<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 18:14
 *
 * [Пример вызова из шаблона]: {{ widget('Blog','images',[1,2]) }}
 */
namespace Core\View\Helper;


return
    /**
     * widget
     *
     * <pre>
     * <code>
     * $this->widget($module, $controller, array $params);
     * </code>
     * </pre>
     *
     * @param string $module
     * @param string $widget
     * @param array $params
     * @return View
     */

    function ($module, $widget = null, $params = array()) {
        /** @var View $this */
        $_this = \Core\Helper\Registry::getInstance()->view;

        // Bug с параметрами
        $mdl = false;

        if(is_array($module)){
            if(isset($module[0])){
                $mdl = $module[0];
            }
            if(isset($module[1])){
                $widget = $module[1];
            }
            if(isset($module[2])){
                $params = $module[2];
            }
        }

        if($mdl !== false){
            $module = $mdl;
        }

        //fb('Widget not found');


        /*

        fb($text);



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


        */

        $application = app();
        try {
            $widgetClosure = $application->widget($module, $widget);
            call_user_func_array($widgetClosure, $params);
        } catch (AclException $e) {
            // nothing for Acl exception
            return null;
        } catch (\Exception $e) {
            if (app()->isDebug()) {
                // exception message for developers
                echo
                    '<div class="alert alert-error">' .
                    '<strong>Widget "' . $module . '/' . $widget . '"</strong>: ' .
                    $e->getMessage() .
                    '</div>';
            }
        }

    };