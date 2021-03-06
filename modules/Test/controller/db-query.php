<?php
/**
 * Example of DB Query builder usage
 *
 * @author   Anton Shevchuk
 * @created  07.06.13 18:28
 */
namespace Application;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \closure $bootstrap
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'DB Query Builders',
        )
    );
    // all examples inside view
};
