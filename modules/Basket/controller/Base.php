<?php
return
    /**
     * @param string $question
     * @return \closure
     */
    function ($step = 1,$address_dostavki = null) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view - Bluz\View\View

            $selectBuilder = $db
        ->select('p.products_id')
        ->from('products', 'p')
        ->where("products_id = '$param_3'");
        $product = $selectBuilder->execute();

        if(isset($product[0]['products_id']))
        return $app_object->dispatch('catalog','products', array('products_id' => $product[0]['products_id']));


        throw new \Bluz\Application\Exception\ApplicationException("Такого продукта нет",404);
        return $app_object->dispatch('catalog','products', array('продукт' => $param_4));

         */


        # Инициализация
        $app_object = app()->getInstance();

        $db =  app()->getDb();

        $crumbs_arr = array();

        $products = null;

        $next_step = $step+1;
        $next_step_head = "Выбрать адрес доставки"; // Оформить Заказ
        $next_step_link = "asd";

        $is_ajax = false; // Запрос пришел не через аякс

        $self_name = "Корзина";

        $basket = app()->getSession()->basket;

        $is_ajax = false;
        if($app_object->getRequest()->isXmlHttpRequest())
            $is_ajax = true;

        $user = app()->getAuth()->getIdentity();

        $total_discounted = null;

        # Тело
        $crumbs_arr =  array(
            // $view->ahref('Каталог', array('каталог', '') ),
            __($self_name),
        );

        $basket['step'] = $step;
        app()->getSession()->basket = $basket;

        switch($step){
            case 1:
                if(isset($basket['products'])){
                    if( is_array($basket['products']) ){
                        $products = array();

                        foreach($basket['products'] as $products_id => $products_num){
                            /*$selectBuilder = $db
                                ->select('p.*')
                                ->from('products', 'p')
                                ->where("products_id = '$products_id'");
                            $product = $selectBuilder->execute();
                            */

                            $product = $db->fetchRow(" SELECT *
                                                FROM products
                                                WHERE products_id = $products_id
                                                ");

                            $product['products_num'] = $products_num;

                            $product['products_total'] = $products_num * $product['products_shoppingcart_price'];

                            $products[] = $product;
                        }

                    } else {
                        // Корзина пуста
                        $view->empty_basket = 1;
                        unset(app()->getSession()->basket);
                    }
                } else {
                    // Корзина пуста
                    $view->empty_basket = 1;
                    unset(app()->getSession()->basket);
                }
                break;

            case 2:
                // Ничего не делать
                $next_step_head = "Выбрать способ оплаты";

                if(isset( $basket['address_dostavki']))
                     $view->address_dostavki =  $basket['address_dostavki'];

                break;

            case 3:
                // Внести адрес доставки
                // TODO проверить длоступное количество продуктов

                $next_step_head = "Подтвердить заказ";

                $basket['address_dostavki'] = $address_dostavki;

                $total = 0;
                if(isset($basket['products']))
                    if( is_array($basket['products']) ){
                        $products = array();

                        foreach($basket['products'] as $products_id => $products_num){

                            $product = $db->fetchRow(" SELECT *
                                            FROM products
                                            WHERE products_id = $products_id
                                            ");

                            $product['products_num'] = $products_num;

                            $product_total = $products_num * $product['products_shoppingcart_price'];

                            $total += $product_total;
                        }
                    }

                $basket['total'] = $total;

                $discount = (int)$user->discount;

                if($discount > 0 AND $discount < 100){
                    // Применить скидку
                    $discount_summ = $total * $discount / 100;
                    $total_discounted = $total - $discount_summ;
                    $basket['total'] = $total_discounted;
                }

                $view->total = $total;
                $view->total_discounted = $total_discounted;

                app()->getSession()->basket = $basket;

                break;

            case 4:
                // Создать Ордер
                // Очистить корзину
                // TODO снять деньги ссо счета
                // TODO логирование
                // TODO если есть скидка, применить ее
                // TODO сделать класс ордера и перенести логику в него

                $credit = $user->getCredit();

                if($credit < $basket['total'])
                    throw new \Application\Exception("Не достаточно средств");

                $insertBuilder        = $db
                    ->insert( 'orders' )
                    ->set( 'users_id', $user->id )
                    ->set( 'address', $basket['address_dostavki'] )
                    ->set( 'total', $basket['total'] )
                ;
                $orders_id = $insertBuilder->execute();

                foreach($basket['products'] as $products_id => $products_num){
                    $product = $db->fetchRow ("SELECT * FROM products WHERE  products_id = '$products_id' ");

                    $insertBuilder        = $db
                        ->insert( 'order_products' )
                        ->set( 'orders_id', $orders_id )
                        ->set( 'products_id', $products_id )
                        ->set( 'price', $product['products_shoppingcart_price'] )
                        ->set( 'products_num', $products_num )
                    ;
                    $insertBuilder->execute();
                }

                $orders_to_bonus = $user->orders_to_bonus;
                $orders_to_bonus--;
                app()->getSession()->identity->orders_to_bonus = $orders_to_bonus;

                $updateBuilder = $db
                    ->update('users')
                    ->setArray(
                        array(
                            'credit' => $credit - $basket['total'],
                            'orders_to_bonus' => $orders_to_bonus
                        )
                    )
                    ->where('id = ?', $user->id);
                $updateBuilder->execute();

                unset(app()->getSession()->basket);

                break;
        }







        # Вывод
        $view->title($self_name,"append"); // rewrite

        $view->products = $products;
        $view->step = $step;



        $view->next_step = $next_step;
        $view->next_step_head = $next_step_head;
        $view->next_step_link = $next_step_link;

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        if($is_ajax)
            $app_object->useLayout(false);
        else
            $app_object->useLayout('front_end.phtml');

    };