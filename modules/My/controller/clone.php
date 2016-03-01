<?php
/*
 * URL для вызова: http://127.0.0.1/zoqa/my/base/question/2+2
 *
 * TODO Рассмотреть
 * - как сделать возможность вызова метода по-зендовски (через имя точки входа)
 * - попробовать взять параметры из урла прямо здесь (в замыкании)
 *
 * - прикрутить шаблонизатор
 *
 * [пример аякс]
 * var basePath = 'http://127.0.0.1/zoqa/'
$.post(basePath+"my/Base/вопрос/78", {asd: 'asd'}, function (res) {
    }, "json");
 */

//TODO доставать продукты из сессии

// TODO очистить сессию

use Application\PaymentTypes;

return
    /**
     * @return \closure
     * @privilege Edit
     */
    function () use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
        $app_object = app()->getInstance();

        // $uri_param_2 = app()->getRequest()->get(2);

        // change layout
        // $app_object->useLayout('front_end.phtml');

        $app_object->useLayout(false);

        $params = $app_object->getRequest()->getParams();

        $tmp = array();

        $products = app()->getBasket()->getItems();

        if( sizeof($products) < 1 )
            throw new \Exception('Корзина пуста');



        if(sizeof($params)){
            foreach($params as $key => $val){
                if($key == "product_price"){
                    if(is_array($val)){
                        // foreach($val as $products_id => $product_shoppingcart_price){  }
                    }
                } elseif($key == "products_num"){
                    if(is_array($val)){
                        foreach($val as $products_id => $product_num){
                            if($product_num > 0){
                                $tmp[$products_id] = $product_num;
                            }
                        }
                    }
                }
            }
            $data['products'] = $tmp;
        }

        @$payment_types_id          = (int)$params['payment_types_id'];
        if($payment_types_id <= 0){
            throw new \Application\Exception("Не выбран способ оплаты");
        }

        $payment_types_key         = PaymentTypes\Table::getInstance()->findRow(['payment_types_id' => $payment_types_id])->key;

        $data['address']           = $params['address_dostavki'];
        $data['notes']             = $params['order_notes'];
        $data['payment_types_id']  = $payment_types_id;
        $data['payment_types_key'] = $payment_types_key;
        $data['order_type']        = Application\Orders\Table::ORDERTYPE_FRONTEND_CLONED;
        $data['user']              = app()->getAuth()->getIdentity();
        $data['parent_id']         = $params['orders_id'];

        if (isset($params['delivery_date'])) {
            $data['delivery_date'] = $app_object->getDate()->prepare($params['delivery_date']);
        }


        var_dump($data);
        die;

        try {

            $crudController = new \Bluz\Controller\Crud();
            $orderCrud      = \Application\Orders\Crud::getInstance();
            $crudController->setCrud($orderCrud);
            $crudController->forceData($data);

            $result = $crudController();

        } catch (\Bluz\Application\Exception\OrderException $e) {
            $code = $e->getCode();
            //TODO записывать в лог
            //throw new \Application\Exception($e->getMessage());
        }

        $new_orders_id = app()->getRegistry()->new_orders_id;

        if ((int)$new_orders_id > 0) {
            $message = "Заказ №$new_orders_id создан";
            $message_type = "Ok";
            $alert_class = "alert-success";
        } else {
            $message = "Заказа не был создан";
            $message_type = "Ошибка";
            $alert_class = "alert-error";
        }

        $view->message = $message;
        $view->message_type = $message_type;
        $view->alert_class = $alert_class;


        //$title = 'Личный кабинет';

        //$app_object->getLayout()->title($title);

        /*
        $crumbs_arr = array(
            $view->ahref('Автор', array('автор', '') ),
            __($title)
        );
        */

        //$app_object->getLayout()->breadCrumbs($crumbs_arr);


        return 'clone.phtml';
    };