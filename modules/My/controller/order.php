<?php

use Application\Orders;

//TODO при выходе со страницы через кнопку Клонировать, очищать корзину

//TODO при добавлении продукта к клонированному ордеру - обнвлять гриду

return
    /**
     * @return \closure
     * @param string operation
     * @param integer orders_id
     * @privilege Edit
     */
    function ($operation = 'view',$orders_id = null,$products_id = null,$products_num = null) use ($view) {

        $title = 'Личный кабинет';

        $user = app()->getAuth()->getIdentity();

        if (!is_object($user))
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        $view->users_id = $user->id;

        $header = 'Заказ';

        #
        switch($operation){
            case 'view':
                //TODO
                break;

            case 'update-product':
                // Изменить харакетристики продукта в заказе
                //$params = [];
                //$params['products_num'] = $products_num;
                //$order = Application\Orders\Crud::getInstance()->readOne(['orders_id' => $orders_id]);
                //$order->updateProduct($products_id,$params,$orders_id);

                if( $products_num == 0 )
                    app()->getBasket()->removeProduct($products_id);
                else
                    app()->getBasket()->updateProduct($products_id,$products_num);

                break;

            case 'add-product':

                // Присоединить продукт к заказу
                if( $orders_id AND $products_id ){
                    //$order = Application\Orders\Crud::getInstance()->readOne(['orders_id' => $orders_id]);
                    //$order->addProduct($products_id);

                    app()->getBasket()->putProduct($products_id,$products_num);
                }
                break;

            case 'delete-product':
                // Удалить продукт из заказа
                if( $orders_id AND $products_id ) {
                    //$order = Application\Orders\Crud::getInstance()->readOne([ 'orders_id' => $orders_id ]);
                    //$order->deleteProduct($products_id);

                    app()->getBasket()->removeProduct($products_id);
                }
                break;

            case 'clone':
                if(is_null($orders_id))
                    return false;

                $order = Application\Orders\Crud::getInstance()->readOne([ 'orders_id' => $orders_id ]);

                $basket = app()->getBasket();

                $basket->flush();

                $basket->set( $order->getData()['products'] );

                $check_orders_id = Orders\Table::getInstance()->findRow(['orders_id' => $orders_id]);
                if(!$check_orders_id)
                    throw new \Application\Exception("Нет такого заказа");

                $view->orders_id = $orders_id;
                $header = 'Клонирование заказа ' . $orders_id;
                break;
        }

        $view->operation = $operation;

        #
        app()->getLayout()->breadCrumbs(
            array(
                $view->ahref($title, array('кабинет', '')),
                $view->ahref('Мои заказы', array('кабинет', 'мои_заказы')),
                $header,
            )
        );

        app()->getLayout()->title($header);

    };