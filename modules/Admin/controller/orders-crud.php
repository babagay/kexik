<?php

namespace Application;

use Application\Orders;
use Bluz\Controller;

return function () use($view) {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Orders\Crud::getInstance());

    if( app()->getRequest()->isPost() ){
        $params = app()->getRequest()->getAllParams();
        if(isset($params['orders_id']))
            if((int)$params['orders_id'] === 0){
            // создается новый заказ
                Orders\Crud::getInstance()->setUser(app()->getAuth()->getIdentity());
            }
    }

    $PaymentTypes = PaymentTypes\Table::getInstance()->getPaymentTypes();
    $view->payment_types = $PaymentTypes;

    return $crudController();
};
