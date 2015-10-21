<?php
     
     return
             function($categories_id = null,$catid = null,$gid = null,$products_id = null) use ($view) {
     
                 $products_grid_options = array();
                 $products_grid_options['categories_id'] = $categories_id;
     
                 $search = null;
     
                 $params = app()->getRequest()->getParams();
                 $get_params = app()->getRequest()->get;
     
                 if (is_array($get_params)) {
                         foreach ($get_params as $get_param => $val) {
                                 if ($pos = strpos($get_param, 'filter-products_id')) {
                                         $products_grid_options['search-column'] = 'products_id';
                                     } elseif ($pos = strpos($get_param, 'filter-products_name')) {
                                         $products_grid_options['search-column'] = 'products_name';
                                     } elseif ($pos = strpos($get_param, 'filter-products_barcode')) {
                                         $products_grid_options['search-column'] = 'products_barcode';
                                     }
                 }
             }
 
             if (isset($get_params['search-like'])) {
                         if (trim($get_params['search-like']) != '') {
                                 $search = $get_params['search-like'];
                                 $products_grid_options['search'] = $search;
                                 if (!isset($products_grid_options['search-column']))
                                         $products_grid_options['search-column'] = 'products_name';
                 }
             }
 
             if (isset($get_params['gid'])) {
                         if (is_null($gid))
                                 $gid = $get_params['gid'];
             }
 
             if (isset($get_params['products_id'])) {
                         if (is_null($products_id))
                                 $products_id = $get_params['products_id'];
             }
 
             if(isset($params['categories_id'])){
                         if(is_null($catid)){
                                 $catid = $params['categories_id'];
                             }
             }
 
             if (app()->getRequest()->getMethod() == 'DELETE') {
                         if (!is_null($categories_id) AND !is_null($products_id)) {
                                 // Удалить связь продукта с категорией
                                 app()->dispatch('products', 'categories-to-products', ['categories_id' => $categories_id, 'products_id' => $products_id, 'operation' => 'delete']);
                             }
             }
 
             if (app()->getRequest()->getMethod() == 'ADD') {
                         if (!is_null($products_id)) {
                                 app()->dispatch('my', 'order', [ 'products_id' => $products_id, 'operation' => 'add-product']);
                             }
             }
 
             // Грида продуктов категории
             $grid_products = new \Core\Model\Products\SqlGrid($products_grid_options);
             $grid_products->setModule('my');
             $grid_products->setController('grid-add-products');
 
 
                 $grid_products->setLimit(5);
 
             $view->grid_products = $grid_products;
             $view->gid = $gid;
             $view->categories_id = $categories_id;
             $view->catid = $catid;
 
         };