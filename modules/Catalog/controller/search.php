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

        /**
         * array of filters_id
         */
        $filter_origin = null;

        /**
         * array of subcategories
         */
        $filter_subcategory = null;


        if($is_ajax){
            $get_params = $app_object->getRequest()->getAllParams();

            if(isset($get_params['filter-subcategory'])){
                // Задан фильтр по категориям
                $filter_subcategory = explode(",",$get_params['filter-subcategory']);
            }
            if(isset($get_params['filter-origin'])){
                // Задан "оригинальный" фильтр
                $filter_origin = explode(",",$get_params['filter-origin']);
            }
        }

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

        $order = $direction . "-products_shoppingcart_price";
        
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

        if(is_array($products))
        if(!sizeof($products)){

            $search_str = explode(" ",$words_to_search);
            $search_str = implode("%",$search_str);

            $pr_query = "
                    SELECT p.products_id, products_name, manufacturers_id, products_quantity, products_shoppingcart_price, products_unit, products_seo_page_name,
                    pd.products_description, pd.products_image_small

                    FROM products p
                    LEFT JOIN products_description pd ON p.products_id = pd.products_id
                    WHERE   products_name LIKE '%$search_str%'

                  AND products_visibility = 1
                  $direction_condition

                  ";
            $products = $db->fetchAll ($pr_query);
        }

        // Составить строку выбранных products_id
        $products_str = "";
        foreach($products as $product){
            $products_str .= $product['products_id'] . ",";
        }

        if(substr($products_str,-1) == ",")
            $products_str = substr_replace($products_str,"",-1);

        // Если задан производитель
        if(!is_null($manufacturers_id)){

            $filtered_products = array();
            foreach($products as $product){
                if($product['manufacturers_id'] == $manufacturers_id)
                    $filtered_products[] = $product;
            }
            $products = $filtered_products;
        }

        // Если задан оригинальный фильтр
        if(isset($products[0])){
            if(isset($filter_origin[0]))
                if($filter_origin[0] != ""){
                    $products_filtered = array();
                    foreach($filter_origin as $filter){
                        $query = "SELECT products_id
                              FROM filters_to_products
                              WHERE products_id IN($products_str) AND filters_id = '$filter' ";
                        $products_filtered_tmp = $db->fetchAll ($query);

                        if(is_array($products_filtered_tmp))
                        foreach($products_filtered_tmp as $products_filtered_tmp_item ){
                            $products_filtered[$products_filtered_tmp_item['products_id']] = $products_filtered_tmp_item['products_id'];
                        }
                    }


                    if(sizeof($products_filtered)){
                        $tmp = array();
                        foreach($products as $product){
                            foreach($products_filtered as $filtered_id => $product_filtered_id){
                                if($product['products_id'] == $product_filtered_id){
                                    $tmp[] = $product;
                                    continue;
                                }
                            }
                        }
                        $products = $tmp;

                        $actual_filters['filter_origin'] = $filter_origin;

                        $view->actual_filters = $actual_filters;
                    }

                }
        }

        $products_ids = array();
        foreach($products as $product){
            $products_ids[] = $product['products_id'];
        }


        $view->manufacturers_id = $manufacturers_id;
        $view->products = $products;
        $view->products_ids = $products_ids;
        $view->is_search = 1;
        $view->search_text = $words_to_search;
        $view->order = $order;

        if( !$is_ajax )
            $app_object->useLayout('front_end.phtml');

        return 'products.phtml';
    };