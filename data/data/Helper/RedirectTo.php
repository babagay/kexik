<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Application\Helper;

use Bluz\Application\Application;

return
    /**
     * redirect to controller
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return void
     */
    function ($module = 'index', $controller = 'index', $params = array()) {
        /** @var Application $this */

        $mdl = null;

           if(is_array($module)){
               if(isset($module[0])) $mdl = $module[0];
               if(isset($module[1])) $controller = $module[1];
               if(isset($module[2])) $params = $module[2];
           }

        if(!is_null($mdl)) $module = $mdl;





        $url = app()->getRouter()->url($module, $controller, $params);
        app()->redirect($url);
    };
