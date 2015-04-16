<?php
/**
 * User registration
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  09.11.12 13:19
 */
namespace Application;

use Application\Users;
use Bluz\Controller;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    // change layout
    app()->useLayout('small.phtml');

    $app_object = app()->getInstance();

    $is_ajax = false;

    if($app_object->getRequest()->isXmlHttpRequest())
        $is_ajax = true;

    #
    $crudController = new Controller\Crud(); // Bluz\Controller\Crud

    $crudController->setCrud(Users\Crud::getInstance());

    $view->uid = uniqid('form_');



    #
    if($is_ajax){
        $result = $crudController();

        if(app()->getRegistry()->user_created_successfully === true){
            unset(app()->getRegistry()->user_created_successfully);

            if(app()->getRegistry()->user_created_mess)
                app()->getMessages()->addSuccess( app()->getRegistry()->user_created_mess );

            app()->redirectTo('index', 'index');
            return false;
        /*
        if( \Core\Helper\Registry::getInstance()->has('user_created_successfully') ){

            if( \Core\Helper\Registry::getInstance()->user_created_successfully === true ){

                \Core\Helper\Registry::getInstance()->remove('user_created_successfully');
                app()->redirectTo('index', 'index');
                return false;
            }
        */

        } else {

            return function() use($result) {
                return $result;
            };
        }

    } else
        return $crudController();

};
