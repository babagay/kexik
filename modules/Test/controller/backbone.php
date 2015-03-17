<?php
/**
 * Example of backbone usage
 *
 * @category Application
 *
 * @author   dark
 * @created  13.08.13 17:16
 */
namespace Application;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    $_this = app()->getInstance();

    $_this->getLayout()->breadCrumbs(
        array(
            $_this->getLayout()->ahref('Test', array('test', 'index')),
            'Backbone',
             )
    );

    $_this->useLayout('front_end.phtml');

};
