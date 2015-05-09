<?php

namespace Application;

use Application\Orders;
use Bluz\Controller;

return function () {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Orders\Crud::getInstance());




    return $crudController();
};
