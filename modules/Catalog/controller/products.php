<?php
/**
 * TODO можно всегда преобразовывать $категория в cat_id и
 * сделать отдельный класс для выборки продуктов
 *
 * FIXME происходит отвал в Application, если в докКоментах указать кириллическое имя параметра
 *
 * [!] app()->getRequest()->getParam('filter-categories_id') - берёт параметр не зависимо от метода Post или Get
 */

return
    /**
     * @param int $manufacturers_id
     * @return \closure
     */
    function ($категория = null, $продукт = null, $products_id = null, $manufacturers_id = null) use ($view) {

        function filterProducts(array $products, array $filter_origin){
            // Составить строку выбранных products_id
            $products_str = "";
            foreach($products as $product){
                $products_str .= $product['products_id'] . ",";
            }

            if(substr($products_str,-1) == ",")
                $products_str = substr_replace($products_str,"",-1);

            $products_filtered = array();
            foreach($filter_origin as $filter){
                $query = "SELECT products_id
                              FROM filters_to_products
                              WHERE products_id IN($products_str) AND filters_id = '$filter' ";

                $products_filtered_tmp = app()->getDb()->fetchAll ($query);

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
            }

            return $products;
        }

        //$app_object = Application\Bootstrap::getInstance();
        // Альтернатива $_this = $this;
        $app_object = app()->getInstance();

        $db =  app()->getDb();

        $filterKeeper = app()->getFilterKeeper();

        $crumbs_arr = array();

        $is_ajax = false;

        $limit = 500;
        $filter_categories_id = null;
        $filter_manufacturers_id = null;
        $filter_order = null;
        $order = 'products_id';
        $direction = 'asc';
        $page = 1;
        $_categories_id = null;
        $_manufacturers_id = null;

        $get_params = app()->getRequest()->getAllParams();
        //if(isset($get_params['manufacturers_id'])) $manufacturers_id = $get_params['manufacturers_id'];
        //if(isset($get_params['категория'])) $категория = $get_params['категория'];
        if(isset($get_params['filter-categories_id'])) $filter_categories_id = $get_params['filter-categories_id'];
        if(isset($get_params['filter-manufacturers_id'])) $filter_manufacturers_id = $get_params['filter-manufacturers_id'];
        if(isset($get_params['order'])) $filter_order = $get_params['order'];
        if(isset($get_params['page'])) $page = $get_params['page'];

        // Значения фильтров
        if( strstr($filter_categories_id,'eq-') ){
           if(str_replace('eq-','',$filter_categories_id) != $filter_categories_id)
              $_categories_id = str_replace('eq-','',$filter_categories_id);
           //fb($_categories_id);
        }

        if( strstr($filter_manufacturers_id,'eq-') ){
           if(str_replace('eq-','',$filter_manufacturers_id) != $filter_manufacturers_id)
              $_manufacturers_id = str_replace('eq-','',$filter_manufacturers_id);
           //fb($_manufacturers_id);
        }

        if( $filter_order ){
            if(strstr($filter_order,'asc-')){
                $order = str_replace('asc-','',$filter_order);
                $direction = 'asc';
            } elseif(strstr($filter_order,'desc-')){
                $order = str_replace('desc-','',$filter_order);
                $direction = 'desc';
            }
        }

        if(is_null($manufacturers_id))
            $manufacturers_id = $_manufacturers_id;
        if(is_null($категория)){
            $categories_id = $категория = $_categories_id;
        }

        if($manufacturers_id == 'all' OR $manufacturers_id == '') $manufacturers_id = null;

        //fb(app()->getInstance()->getRequest()->getHeader('X-Requested-With') == 'XMLHttpRequest');
        //fb($app_object->getRequest()->isXmlHttpRequest());
        //fb($app_object->getRequest()->isPost());
        if($app_object->getRequest()->isXmlHttpRequest())
            $is_ajax = true;

        //fb($get_params );

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

        if(!is_null($категория)){
            // Вывести все продукты категории

            if(is_null($manufacturers_id)){
                $selectBuilder = $db
                    ->select('c.categories_id, c.categories_name,  c.categories_seo_page_name, c.parent_id')
                    ->from('categories', 'c')
                    ->where("categories_seo_page_name = '$категория'")
                    ->orWhere("categories_id = '$категория'")

                ;
                $category = $selectBuilder->execute();

                if(!isset($category[0]['categories_id']))
                    throw new \Bluz\Application\Exception\ApplicationException("Такой категории нет",404);

                if($category[0]['categories_seo_page_name'] != '')
                    $view->category_name = $category[0]['categories_seo_page_name'];
                else
                    $view->category_name = $category[0]['categories_id'];

                $view->categories_id = $category[0]['categories_id'];

                // Взять parent_id
                $category_parent = null;
                if( (int)$category[0]['parent_id'] > 0 ){
                    $selectBuilder = $db
                        ->select('c.categories_id, c.categories_name, c.categories_seo_page_name')
                        ->from('categories', 'c')
                        ->where("categories_id = '{$category[0]['parent_id']}'");
                    $category_parent = $selectBuilder->execute();

                    if($category_parent[0]['categories_seo_page_name'] != '') $category_parent = $category_parent[0]['categories_seo_page_name'];
                    else $category_parent = $category_parent[0]['categories_id'];

                    $view->category_parent = $category_parent;
                }

                $products = $db->fetchAll ("SELECT * FROM products_to_categories  WHERE categories_id = {$category[0]['categories_id']} ");

                $categories_id = $category[0]['categories_id'];

                $manufacturers_id = 'all';

                if( count($products) > 0){
                    $tmp = array();
                    foreach($products as $pr_item){
                        $tmp[] = $pr_item['products_id'];
                    }
                    $tmp = implode(',', $tmp);

                    $pr_query = "
                            SELECT p.*,
                             pd.products_description, pd.products_image_small
                            FROM products p
                            LEFT JOIN products_description pd ON p.products_id = pd.products_id
                            WHERE p.products_id IN ($tmp)
                                AND p.products_visibility = 1
                            ORDER BY p.{$order} {$direction}
                            ";
                    $products = $db->fetchAll ($pr_query);

                    // наложение оригинальных фильтров
                    if(isset($products[0]))
                        if(isset($filter_origin[0]))
                            if($filter_origin[0] != ""){
                                $products = filterProducts($products,$filter_origin );

                                $products_ids = array();
                                foreach($products as $product){
                                    $products_ids[] = $product['products_id'];
                                }
                                $view->products_ids = $products_ids;
                                $actual_filters['filter_origin'] = $filter_origin;

                                $view->actual_filters = $actual_filters;
                            }

                    // вывод
                    $view->products = $products;

                } else {
                    fb("test metka zxcvfgbe");
                }

                if(isset($category_parent)){
                    $crumbs_arr =  array(
                        // $view->ahref('Каталог', array('каталог', '') ),
                        __('Каталог'),
                        __($category_parent),
                        __($category[0]['categories_name']),
                    );
                } else {
                    $crumbs_arr =  array(
                        // $view->ahref('Каталог', array('каталог', '') ),
                        __('Каталог'),
                        __($category[0]['categories_name']),
                    );
                }

                $view->title($category[0]['categories_name'],"append");

            } else {
                // Эта секция выполняется только через аякс

                $products = array();

                if($manufacturers_id == 'all'){
                    // Сбросить фильтр по производителю, но оставить по категории
                    $products = $db->fetchAll ("SELECT * FROM products_to_categories WHERE categories_id = '$категория' " );

                    //TODO проверить, не пустой ли массив

                    $tmp = array();
                    foreach($products as $pr_item){
                        $tmp[] = $pr_item['products_id'];
                    }
                    $tmp = implode(',', $tmp);

                    $products = $db->fetchAll ("
                            SELECT p.*,
                            pd.products_description, pd.products_image_small
                            FROM products p, products_description pd
                            WHERE p.products_id > 0 AND p.products_id = pd.products_id
                            AND p.products_id IN($tmp)
                            AND p.products_visibility = 1
                            ");
                    //LEFT JOIN manufacturers m ON pd.manufacturers_id = m.manufacturers_id

                    // [!] все сгенерированные к данному моменту фильтры производителей остаются в кеше filterKeeper'a

                } else {
                    // Добавить фильтр по производителю и по категории
                    $products = $db->fetchAll ("SELECT * FROM products_to_categories WHERE categories_id = '$категория' " );

                    //TODO проверить, не пустой ли массив

                    $tmp = array();
                    foreach($products as $pr_item){
                        $tmp[] = $pr_item['products_id'];
                    }
                    $tmp = implode(',', $tmp);

                    $products = $db->fetchAll ("
                            SELECT p.*
                            FROM products p
                            LEFT JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id

                            WHERE p.products_id > 0
                            AND p.products_id IN($tmp)
                            AND m.manufacturers_id = '$manufacturers_id'
                            AND p.products_visibility = 1
                            ORDER BY p.{$order} {$direction}
                            ");
                    //LEFT JOIN  products_description pd ON p.products_id = pd.products_id
                    //TODO категорию

                }

                $products_ids = array();
                // наложение оригинальных фильтров
                $filters_defined = false;
                if(isset($products[0])){
                    if(isset($filter_origin[0])){
                        if($filter_origin[0] != ""){

                            $products = filterProducts($products,$filter_origin );

                            foreach($products as $product){
                                $products_ids[] = $product['products_id'];
                            }
                            $filters_defined = true;
                            //$view->products_ids = $products_ids;
                            $actual_filters['filter_origin'] = $filter_origin;
                            $view->actual_filters = $actual_filters;
                        }
                    }  else {
                    }
                }


                if($filters_defined === false){
                    // FIXME зачем этот блок кода?
                    // если выбран производитель, отображать только привязанные к нему фильтры
                    $products_ids = array();
                    foreach($products as $product){
                        $products_ids[] = $product['products_id'] ;
                    }
                }

                /*
               if($фильтры_вендора = $filterKeeper->selectContext("vendor",$manufacturers_id)->selectFilterType("origin")->getFilters()){
                   $filters = $фильтры_вендора;
               } else {
                   fb($products);
               }
               */

                $view->products = $products;
                $view->categories_id = $категория;
                $view->products_id = $products_id;
                $view->products_ids = $products_ids;
                $view->manufacturers_id = $manufacturers_id;

                $categories_id = $категория;
            }

        }  elseif(!is_null($продукт)){
            // Попытаться найти продукт по сео-имени или id

            // TODO сначала искать в кэше

            $product = $db->fetchRow("
                        SELECT p.*
                        FROM products p
                        WHERE p.products_id = '$продукт'
                        AND p.products_visibility = 1
                        ");

            if($product === false){
                $product = $db->fetchRow("
                        SELECT pd.*
                        FROM products_description pd
                        WHERE pd.products_seo_page_name = '$продукт'
                        ");
            }

            if( $product === false )
                throw new \Bluz\Application\Exception\ApplicationException("Такого продукта нет",404);

            $cat = $db->fetchRow("
                        SELECT p2c.*, c.categories_name, c.parent_id
                        FROM products_to_categories p2c
                        LEFT JOIN categories c ON c.categories_id = p2c.categories_id
                        WHERE p2c.products_id = '{$product['products_id']}'
                        ");

            $cat_parent = array();
            if( (int)$cat['parent_id'] > 0 ){
                $cat_parent = $db->fetchRow("
                        SELECT c.*
                        FROM categories c
                        WHERE c.categories_id = '{$cat['parent_id']}'
                        ");
            }
            if( isset($cat_parent['categories_name']) ){
                $crumbs_arr =  array(
                   // $view->ahref('Каталог', array('каталог', '') ),
                    __('Каталог'),
                    __($cat_parent['categories_name']),
                    __($cat['categories_name']),
                    __($product['products_name'])
                );
            } else {
                $crumbs_arr =  array(
                    // $view->ahref('Каталог', array('каталог', '') ),
                    __('Каталог'),
                    __($cat['categories_name']), 
                    __($product['products_name'])
                );
            }


            $view->title($product['products_name'],"append");

            $product_descr = $db->fetchRow("
                        SELECT pd.*
                        FROM products_description pd
                        WHERE pd.products_id = '{$product['products_id']}'
                        ");



            $product = $db->fetchRow("
                        SELECT p.*
                        FROM products p
                        WHERE p.products_id = '{$product['products_id']}'
                         AND p.products_visibility = 1
                        ");


            $view->product =  array_merge($product,$product_descr);



        } elseif(!is_null($products_id)){
            // Вывести конкретный продукт

            // TODO сначала искать в кэше

            $product = $db->fetchAll ("SELECT *
             FROM products
              WHERE products_id = '$products_id'
                AND p.products_visibility = 1 " );
            //fb($product);
            $view->product = $product;
        } else {
            // Действие по умолчанию
            // TODO
        }

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        if( !$is_ajax )
        // [!] Для вызова аяксом этот вызов не годится, т.к. он срендерит полную страницу с хедером, боди и тд
             $app_object->useLayout('front_end.phtml');


        // Вывод переменных в шаблон верхнего уровня
        $view->addTwigParam('filter_categories_id','eq-' . $categories_id);
        $view->addTwigParam('filter-manufacturers_id','eq-' . $manufacturers_id);
        $view->addTwigParam('order',$direction . '-' . $order);
        $view->addTwigParam('page',$page);


        // Явно указываем шаблон, чтобы избежать ошибку Unable to find template "products?filter-categories_id=eq-&fi
        return 'products.phtml';

    };