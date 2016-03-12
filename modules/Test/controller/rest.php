<?php
/**
 * Example of REST controller
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  12.08.13 17:23
 */
namespace Application;

use Application\Orders;
use Bluz\Controller;

return function () {
    $restController = new Controller\Rest();
    $restController->setCrud(Orders\Crud::getInstance());

    app()->useLayout('small.phtml');



    return $restController();
};
