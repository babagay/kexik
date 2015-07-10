<?php

namespace Application;

use Application\Categories;
use Bluz\Controller;

$_this = $this;

return function ($parent_id = null,$categories_level = null) use($_this,$view) {
    /**
     * $_this Application\Bootstrap
     * app()->getView()->parent_id =  $parent_id; - не сработало [!]
     */

    $crudController = new Controller\Crud();

    $crudController->setCrud(Categories\Crud::getInstance());

    $params = app()->getRequest()->getParams();

    if(isset($params['parent_id'])) $parent_id = $params['parent_id'];

    $view->parent_id = $parent_id;
    $view->categories_level = $categories_level;

    return $crudController();
};
