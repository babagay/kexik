<?php

namespace Application;

use Bluz;
use Application\Admin;

$_this = $this;

return
    /**
     * @return \closure
     */
    function ($categories_id = null) use ($view, $module, $controller) {
        /**
         * @var \Application\Bootstrap $this
         * @var \Bluz\View\View $view
         */

        //$grid = new  Core\Model\Categories\SelectGrid();
        $grid = new  Categories\SelectGrid();
        $grid->setModule($module);
        $grid->setController($controller);

        $view->grid = $grid;

        //return 'grid-sql.phtml';
    };