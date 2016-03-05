<?php
/**
 * JS practice
 */
namespace Application;

return
    /**
     * @return \closure
     */
    function ( ) use ($view) {

        $_this = app()->getInstance();

        $_this->getLayout()->breadCrumbs(
            array(
                $view->ahref('Test', array('test', 'index')),
                'JS practice',
            )
        );




        $_this->useLayout('small.phtml');
    };