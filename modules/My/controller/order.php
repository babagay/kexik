<?php

use Application\Orders;

return
    /**
     * @return \closure
     * @param string operation
     * @param integer orders_id
     * @privilege Edit
     */
    function ($operation = 'view',$orders_id = null) use ($view) {

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
            case 'edit':
                //TODO
                break;
            case 'add-product':
                //TODO
                fb('add-product 456454665456');
                break;
            case 'clone':
                if(is_null($orders_id))
                    return false;

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