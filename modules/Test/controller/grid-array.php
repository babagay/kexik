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
    /* $this->*/
    app()->getInstance()->
    getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Grid with Array',
        )
    );
    $grid = new \Core\Model\ArrayGrid();
    $grid->setModule($module);
    $grid->setController($controller);

    $view->grid = $grid; // Core\Model\ArrayGrid

    $emptyRows = 3;
    if ($grid->pages() > 1 && sizeof($grid->getData()) < $grid->getLimit())
        $emptyRows = $grid->getLimit() - sizeof($grid->getData());

    // fb($grid->limit(50)); // работает

    /*
    fb($grid->pages() );
    fb(sizeof($grid->getData()));
    fb( $grid->getLimit());
    fb($grid->getLimit() - sizeof($grid->getData())); // 0
    */

    $view->emptyRows = $emptyRows;
};
