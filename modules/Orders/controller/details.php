<?php

namespace Application;

//use Application\Categories;
use Bluz\Controller;

return
    /**
     * @param integer $orders_id
     * @param string $operation
     * @privilege Edit
     */
    function ($orders_id = null, $operation = null) use ($view) {

        $get_params = app()->getRequest()->get;

        if(isset($get_params['orders_id'])){
            if(is_null($orders_id))
                $orders_id = $get_params['orders_id'];
        }
        if(isset($get_params['operation'])){
            if(is_null($operation))
                $operation = $get_params['operation'];
        }


        #
        $crudController = new Controller\Crud();
        $crudController->setCrud(Orders\Crud::getInstance());
        $order = $crudController();

        #
        if( $operation == 'change_datails' ){
            return null;
        }

        $view->orders_id = $orders_id;
        $view->order     = $order;

        $selected_status = $order['row']->order_status;
        $view->order_status = app()->getView()->select("order_status",Orders\Table::getInstance()->order_status_arr ,$selected_status,['id' => 'order_status'])    ;


    };
