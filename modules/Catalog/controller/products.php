<?php
/**
 * TODO можно всегда преобразовывать $категория в cat_id и
 * сделать отдельный класс для выборки продуктов
 */

return
    /**
     * @param string $question
     * @return \closure
     */
    function ($категория = null, $продукт = null, $products_id = null, $manufacturers_id = null) use ($view) {
        /**
         * @var Вмsесто Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
        // Альтернатива $_this = $this;
        $app_object = app()->getInstance();

        $db =  app()->getDb();

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
                            ORDER BY p.{$order} {$direction}
                            ";
                    $products = $db->fetchAll ($pr_query);

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
                            SELECT p.*, pd.*
                            FROM products p, products_description pd
                            WHERE p.products_id > 0 AND p.products_id = pd.products_id
                            AND p.products_id IN($tmp)
                            ");
                    //LEFT JOIN manufacturers m ON pd.manufacturers_id = m.manufacturers_id
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
                            SELECT p.*, pd.*
                            FROM products p
                            LEFT JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
                            LEFT JOIN  products_description pd ON p.products_id = pd.products_id
                            WHERE p.products_id > 0
                            AND p.products_id IN($tmp)
                            AND m.manufacturers_id = '$manufacturers_id'
                            ORDER BY p.{$order} {$direction}
                            ");
                    //TODO категорию

                }



                $view->products = $products;
                $view->categories_id = $категория;
                $view->products_id = $products_id;
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
                        ");


            $view->product =  array_merge($product,$product_descr);



        } elseif(!is_null($products_id)){
            // Вывести конкретный продукт

            // TODO сначала искать в кэше

            $product = $db->fetchAll ("SELECT * FROM products WHERE products_id = '$products_id' " );
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