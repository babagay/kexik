<?php

namespace Application;

use Application\Categories;
use Bluz\Controller;
 
return function () {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Users\Crud::getInstance());   

    return $crudController();
};
