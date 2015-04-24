<?php

namespace Application;

use Application\Products;
use Bluz\Controller;

return function () {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Products\Crud::getInstance());





    return $crudController();
};