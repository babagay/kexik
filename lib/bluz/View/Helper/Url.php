<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\View\Helper;

use Bluz\Application\Application;
use Bluz\View\View;
use Bluz\View\ViewException;

/* FIXME Проблема 'all-for-one' (все на одного)
 * [?] Вопрос номер 1: почему при вызове через call_user_method_array()
 *      php кладет весь массив аргументов в первый параметр замыкания (в данном случве - в $module)
 *
 * [!] Hook для эмуляции контекстной переменной $this
 *      $_this = $this;
 *      use($_this)
 */

/// $_this = $this;

return
    /**
     * @param string $module
     * @param string $controller
     * @param array $params
     * @param bool $checkAccess
     * @return string|null
     */
    function ($module, $controller = null , array $params = array(), $checkAccess = false) /* use($_this) */ {
    /** @var View $this. В php 5.3 это $_this */
    $app = app();

    // Решение проблемы "все на одного" (all-for-one)
    if(isset($module[3])) $checkAccess = $module[3];
    if(isset($module[2])) $params = $module[2];
    if(isset($module[1])) $controller = $module[1];
    if(isset($module[0])) $module = $module[0];


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
