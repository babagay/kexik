<?php
/**
 *
 */

use Application\PaymentTypes;

return
    /**
     * @param integer $step
     * @param integer $payment_types_id
     * @param string $address_dostavki
     * @param string $order_notes
     * @return \closure
     */
    function ($step = 1, $address_dostavki = null, $order_notes = null, $delivery_phone = null, $delivery_date = null, $payment_types_id = null) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view - Bluz\View\View
         */

        if (app()->getSession()->rollback)
            unset(app()->getSession()->rollback);

        # Инициализация
        $app_object = app()->getInstance();

        $db = app()->getDb();

        $crumbs_arr = array();

        $products = null;

        $next_step      = $step + 1;
        $next_step_head = "Выбрать адрес доставки"; // Оформить Заказ
        $next_step_link = "asd";

        $is_ajax = false; // Запрос пришел не через аякс

        $self_name = "Корзина";

        $basket = app()->getSession()->basket;

        $is_ajax = false;
        if ($app_object->getRequest()->isXmlHttpRequest())
            $is_ajax = true;

        $user = app()->getAuth()->getIdentity();

        $total_discounted = $basket_total = null;

        # Тело
        $crumbs_arr = array(
            // $view->ahref('Каталог', array('каталог', '') ),
            __($self_name),
        );

        $basket['step']             = $step;
        app()->getSession()->basket = $basket;

        switch ($step) {
            case 1:
                if (isset($basket['products'])) {
                    if (is_array($basket['products'])) {
                        if (sizeof($basket['products'])) {
                            $products = array();

                            foreach ($basket['products'] as $products_id => $products_num) {
                                /*
                                $selectBuilder = $db
                                    ->select('p.*')
                                    ->from('products', 'p')
                                    ->where("products_id = '$products_id'");
                                $product = $selectBuilder->execute();
                                */

                                if ((int)$products_id == 0) {
                                    unset($basket['products'][$products_id]);
                                    continue;
                                }

                                $query_fetch_product = "SELECT *
                                                FROM products
                                                WHERE products_id = $products_id";

                                $product = $db->fetchRow($query_fetch_product);

                                $product['products_num'] = $products_num;

                                $product['products_total'] = $products_num * $product['products_shoppingcart_price'];

                                $products[] = $product;
                            }

                            if (sizeof($products)) {
                                foreach ($products as $product) {
                                    $basket_total += $product['products_total'];
                                }
                                $view->basket_total = $basket_total;
                            }

                            app()->getSession()->basket = $basket;
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
                } else {
                    // Корзина пуста
                    $view->empty_basket = 1;
                    unset(app()->getSession()->basket);
                }
                break;

            case 2:
                // Ничего не делать
                $next_step_head = "Выбрать способ оплаты";

                if (isset($basket['address_dostavki']))
                    $view->address_dostavki = $basket['address_dostavki'];
                else
                    $view->address_dostavki = $user->delivery_address_1;

                if (isset($basket['delivery_phone']))
                    $view->delivery_phone = $basket['delivery_phone'];
                else
                    $view->delivery_phone = $user->phone;

                if (isset($basket['delivery_date']))
                    $view->delivery_date = $basket['delivery_date'];
                else {
                    $view->delivery_date = $app_object->getDate()->now('d/m/Y H:i'); // 2015-06-05 16:47[:s]
                }

                if (isset($basket['order_notes']))
                    $view->order_notes = $basket['order_notes'];

                break;

            case 3:
                $next_step_head = "Подтвердить заказ";

                $basket['address_dostavki'] = $address_dostavki;
                $basket['order_notes']      = $order_notes;
                $basket['delivery_phone'] = $delivery_phone;
                $basket['delivery_date'] = $delivery_date;

                $basket['payment_types_id'] = 60;

                $total = 0;
                if (isset($basket['products']))
                    if (is_array($basket['products'])) {
                        $products = array();

                        foreach ($basket['products'] as $products_id => $products_num) {

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

                $discount = 0;
                if (is_object($user))
                    $discount = (int)$user->discount;

                if ($discount > 0 AND $discount < 100) {
                    // Применить скидку
                    $discount_summ    = $total * $discount / 100;
                    $total_discounted = $total - $discount_summ;
                    $basket['total']  = $total_discounted;
                }

                $payment_types = PaymentTypes\Table::getInstance()->getPaymentTypes();

                // Способы оплаты
                $checkbox_group = '<h4>Оплата производится:</h4>';
                foreach ($payment_types as $payment_type) {
                    $checkbox_group .= "<div>";
                    $checkbox_group .= $view->radio('payment_types_id', $payment_type['payment_types_id'], false, []);
                    $checkbox_group .= " <label for=\"payment_types_id\">{$payment_type['pay_by']}</label>";
                    $checkbox_group .= "</div>";
                }

                $view->checkbox_group   = $checkbox_group;
                $view->total            = $total;
                $view->total_discounted = $total_discounted;

                app()->getSession()->basket = $basket;

                break;

            case 4:
                // Создать Заказ

                $credit = 0;
                if (is_object($user))
                    $credit = $user->getCredit();
                else {
                    app()->getSession()->rollback = $view->baseUrl("корзина");
                    throw new \Bluz\Auth\AuthException("Для совершения покупок нужна  <a href=\"" . $view->baseUrl('вход') . "\">авторизация</a> ");
                }

                $payment_types_key = PaymentTypes\Table::getInstance()->findRow(['payment_types_id' => $payment_types_id])->key;

                $data                      = app()->getSession()->basket;
                $data['address']           = $data['address_dostavki'];
                $data['notes']             = $data['order_notes'];
                $data['payment_types_id']  = $payment_types_id;
                $data['payment_types_key'] = $payment_types_key;
                $data['order_type']        = Application\Orders\Table::ORDERTYPE_FRONTEND;
                $data['user']              = $user;

                if (isset($data['delivery_date'])) {
                    $data['delivery_date'] = $app_object->getDate()->prepare($data['delivery_date']);
                }

                $tmp = array();
                foreach ($data['products'] as $id => $num) {
                    if ($num > 0)
                        $tmp[$id] = $num;
                }
                $data['products'] = $tmp;

                if (!sizeof($data['products']))
                    throw new \Application\Exception("Нет товаров в корзине");

                $message = "";
                switch ($payment_types_key) {
                    case 'cache':
                        $message = "Итоговая сумма будет востребована при выдаче заказа";
                        break;
                    case 'credit':
                        $message = "Итоговая сумма будет снята с текущего баланса пользователя";
                        break;
                    default:
                        throw new \Application\Exception("Не допустимый способ оплаты");
                        break;
                }

                // Force total calc in createOrder method
                unset($data['total']);

                try {
                    $crudController = new \Bluz\Controller\Crud();
                    $orderCrud      = \Application\Orders\Crud::getInstance();
                    $crudController->setCrud($orderCrud);

                    //$orderCrud->setProducts($data['products']);
                    //$orderCrud->setUser($user);
                    //\Application\Orders\Crud::getInstance()->setTotal($basket['total']);
                    $crudController->forceData($data);

                    $result = $crudController();

                } catch (\Bluz\Application\Exception\OrderException $e) {
                    $code = $e->getCode();
                    throw new \Application\Exception($e->getMessage());
                }

                $new_orders_id = app()->getRegistry()->new_orders_id;

                if ((int)$new_orders_id > 0) {
                    // Ok
                    $view->new_orders_id  = $new_orders_id;
                    $view->new_order_mess = "Спасибо за заказ!";

                    if (app()->getSession()->identity->orders_to_bonus == 10) {
                        $view->present = "Вам положен маленький презент от нас! ";
                    }

                    $view->message = $message;

                } else {
                    $mess   = "<h3> <b> Заказ не был создан </b> </h3>";
                    $errors = $orderCrud->getErrors();

                    if (is_array($errors)) {
                        if (sizeof($errors)) {
                            foreach ($errors as $fieldname => $error) {
                                $error_field = "<h4>$fieldname:</h4>";
                                $error_field .= "<ul>";
                                foreach ($error as $err) {
                                    $error_field .= "<li>$err</li>";
                                }
                                $error_field .= "</ul>";
                                $mess .= $error_field;
                            }
                        }
                    }
                    $view->new_order_mess = $mess;
                }

                unset(app()->getRegistry()->new_orders_id);

                unset(app()->getSession()->basket);


                break;
        }


        # Вывод
        $view->title($self_name, "append"); // rewrite

        $view->products = $products;
        $view->step     = $step;


        $view->next_step      = $next_step;
        $view->next_step_head = $next_step_head;
        $view->next_step_link = $next_step_link;

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        if ($is_ajax)
            $app_object->useLayout(false);
        else
            $app_object->useLayout('front_end.phtml');

    };