<?php

namespace Application;

//use Application\Users\Table;

return
    /**
     * @return \closure
     */
    function () {
        /**
         * @var \Application\Bootstrap $this
         */
        //$total = $this->getDb()->fetchOne('SELECT COUNT(*) FROM users');
        //$active = $this->getDb()->fetchOne('SELECT COUNT(*) FROM users WHERE status = ?', [Table::STATUS_ACTIVE]);
        //$last = $this->getDb()->fetchRow('SELECT id, login FROM users ORDER BY id DESC LIMIT 1');
        /*
         *     $query = "  SELECT p2c.products_id
                    FROM products_to_categories p2c
                    WHERE categories_id = " . $db->quote($категория);

          $products = $db->fetchAll($query);
         */

        $view = app()->getView();
        $app_object = app()->getInstance();
        $db =  app()->getDb();
        $basket = app()->getSession()->basket;
        $user = app()->getAuth()->getIdentity();
        //$params = $app_object->getRequest()->getParams();

        $header_enter_cabinet = "Войти на сайт";
        $header_basket = "Ваша корзина";
        $link_basket = $view->baseUrl("корзина");
        $link_signup = $view->baseUrl("регистрация");
        $header_signup = "Зарегистрироваться";

        $discount = 0;
        $credit = 0;
        $orders_to_bonus = 10;
        $in_basket = '';

        if(is_object($user)){
            $userData = $db->fetchObject("SELECT * FROM users WHERE id = ". $db->quote($user->id));

            $credit = round($userData->credit * 100,0)/100; // Отсекает 3 и 4 знаки после запятой, не затрагивая значение в базе
            $discount = $userData->discount;
            $orders_to_bonus = $userData->orders_to_bonus;
        }

        if(isset($basket['products'])){
            $products_count = count($basket['products']);

            $product_title = app()->wordEnd("товар",$products_count);

            $in_basket = " - <span id=\"basket_products_counter\">".$products_count . "</span> $product_title";
        }


        $btn_1 = "ВЫХОД";

        $link_enter_cabinet = $view->baseUrl("вход");
        $link_btn_1 = '';

        if ($identity = app()->getAuth()->getIdentity()){
            $header_enter_cabinet = "Здравствуйте, " . $identity->login . "!"; // TODO сделать линк Войти в кабинет
            $link_enter_cabinet = $link_btn_1 = $view->baseUrl("выход");
        }

        //$user = Users\Table::findRow(20);

        //$authTable = Auth\Table::getInstance();
        //$authTable->generateEquals($user, 'test');


        ?>
        <script>
            require(['bluz.widget']);
        </script>

        <div class="cabinet"><a href="<?php echo $link_enter_cabinet; ?>" ><?php echo $header_enter_cabinet; ?></a></div>
        <div class="basket"><a href="<?php echo $link_basket; ?>" > <?php echo $header_basket; ?> <?php echo $in_basket; ?> </a></div>
        <div class="partners">
            <img src="public/images/bank.png">
            <img src="public/images/master.png" class="middle">
            <img src="public/images/post.png">
        </div>
        <?php

        if( !is_null($identity) ){
        ?>
            <div class="cabnet-form">
                <div class="select-cabnet"><span>Личный кабинет:  <span>&#9660;</span>  </span>
                    <ul class="select-no-display">
                        <li><a href="">Система СКИДОК</a></li>
                        <li><a href="">Пополнение счета</a></li>
                    </ul>
                </div>
                <p>Текущая скидка: <span class="discount"><?php echo $discount; ?> %</span></p>
                <p>Баланс: <span class="discount" id="users_credit"><?php echo $credit; ?></span> грн.</p>
                <p>Заказов до ПОДАРКА: <span class="discount"><?php echo $orders_to_bonus; ?></span>  </p>
                <!--button-->
                  <a href="<?php echo $link_enter_cabinet; ?>" ><?php echo $btn_1; ?></a>
                <!--/button-->
            </div>
        <?php
        } else {
            ?>
            <div class="cabinet"><a href="<?php echo $link_signup; ?>" ><?php echo $header_signup; ?></a></div>
            <?php
        }

    return false; // Чтобы не выкидывало ошибку Unable to find template "widget.phtml" при вызове через аякс


    };
