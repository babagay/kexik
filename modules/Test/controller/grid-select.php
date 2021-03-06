<?php
/**
 * Example of grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:08
 */
namespace Application;

use Bluz;
use Application\Test;

return
/**
 * @return \closure
 */
function () use ($view, $module, $controller) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Grid with Select',
        )
    );
    $grid = new Test\SelectGrid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->grid = $grid;

    return 'grid-sql.phtml';
};
