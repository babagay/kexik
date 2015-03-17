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

$_this = $this;

return
/**
 * @return \closure
 */
function () use ($view, $module, $controller, $_this) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $_this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Grid with SQL',
        )
    );
    $grid = new \Core\Model\Test\SqlGrid();
    $grid->setModule($module);
    $grid->setController($controller);
    // just example of same custom param for build URL
    $grid->setParams(array('id'=>5));


    //$grid->setLimit(5);


    $view->grid = $grid;

    $emptyRows = 0;
    if ($grid->page() > 1 && sizeof($grid->getData()) < $grid->getLimit())
        $emptyRows = $grid->getLimit() - sizeof($grid->getData());

    $view->emptyRows = $emptyRows;

    // TODO сделать чтобы линка менялась в зависимости от сортировки
    $view->grid_order_1 = $grid->order('id');
    $view->grid_order_2 = $grid->order('name');
    $view->grid_order_3 = $grid->order('status');

    $view->grid_getLimit =  $grid->getLimit();
    $view->grid_limit_5 = $grid->limit(5);
    $view->grid_limit_25 = $grid->limit(25);
    $view->grid_limit_50 = $grid->limit(50);
    $view->grid_limit_100 = $grid->limit(100);

    $view->prev = $grid->prev();
    $view->next = $grid->next();

    $pages_link = array();
    for($i=1; $i<=$grid->pages(); $i++) {
        $pages_link[$i] = $grid->page( $i );
    }
    $view->pages_link = $pages_link;

};
