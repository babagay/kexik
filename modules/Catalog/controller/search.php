<?php

return

    function ($manufacturers_id = null, $direction = null) use ($view) {
        /**
         * @var Вмsесто Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        $app_object = Application\Bootstrap::getInstance();
        // Альтернатива $_this = $this;
        // $app_object = app()->getInstance();

        $db =  app()->getDb();

        $crumbs_arr = array();

        $is_ajax = false;
        if($app_object->getRequest()->isXmlHttpRequest())
            $is_ajax = true;

        $order = 'products_id';


        if($manufacturers_id == 'all' OR $manufacturers_id == '') $manufacturers_id = null;

        /*
        $manufacturers_condition = '';
        if(!is_null($manufacturers_id)){
            $manufacturers_condition = " AND manufacturers_id = '$manufacturers_id' ";
        }
        */

        $direction_condition = '';
        if(!is_null($direction)){
            $direction_condition = " ORDER BY products_shoppingcart_price $direction ";
        }

        $words_to_search = trim($app_object->getRequest()->get(2));

        // TODO профильтровать с целью секьюрности
        /*
         * Проверить длину
         * убрать все символы кроме допустимых
         * убрать слова типа SELECT
         */

        $words_to_search_arr = explode(" ", $words_to_search);

        $search_text = '';
        for($i=0; $i<count($words_to_search_arr);  $i++){
            if($i == 0){
                $search_text .= "+" . $words_to_search_arr[$i] . ' ';
            } else {
                $search_text .= "+" . $words_to_search_arr[$i] . ' ';
            }
        }
        $search_text = trim($search_text);

        if( $search_text == '')
            throw new \Bluz\Application\Exception\ApplicationException("Текст поиска пуст");

        #
        /*
        $pr_query = "
                            SELECT p.*,
                             pd.products_description, pd.products_image_small
                            FROM products p
                            LEFT JOIN products_description pd ON p.products_id = pd.products_id
                            WHERE p.products_id IN (34,74,76)

                            ";
        */


        $pr_query = "
                    SELECT p.products_id, products_name, manufacturers_id, products_quantity, products_shoppingcart_price, products_unit, products_seo_page_name,
                    pd.products_description, pd.products_image_small,
                          MATCH (products_name) AGAINST ('$search_text' IN BOOLEAN MODE) AS score
                          FROM products p
                    LEFT JOIN products_description pd ON p.products_id = pd.products_id
                    WHERE MATCH (products_name) AGAINST
                          ('$search_text' IN BOOLEAN MODE)
                  AND products_visibility = 1
                  $direction_condition

        ";
        $products = $db->fetchAll ($pr_query);




        $products_ids = array();
        foreach($products as $product){
            $products_ids[] = $product['products_id'];
        }

        if(!is_null($manufacturers_id)){
            $filtered_products = array();
            foreach($products as $product){
                if($product['manufacturers_id'] == $manufacturers_id)
                    $filtered_products[] = $product;
            }
            $products = $filtered_products;
            $view->manufacturers_id = $manufacturers_id;
        }


        $view->products = $products;
        $view->products_ids = $products_ids;
        $view->is_search = 1;
        $view->search_text = $words_to_search;

        if( !$is_ajax )
            $app_object->useLayout('front_end.phtml');

        return 'products.phtml';
    };