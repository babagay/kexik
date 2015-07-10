<?php
/**
 * Created by PhpStorm.
 * User: работа
 * Date: 02.07.15
 * Time: 19:05
 *
 * @privilege LinkProducts2Categories
 * @param integer $categories_id
 * @param integer $catid
 * @param integer $products_id
 * @param string $gid - дополнительный условный идентификатор гриды
 * @return \closure
 *
 * url - http://localhost/kex/admin/categories/categories-to-products/categories_id/13
 * Если выбрать несколько продуктов по поиску,
 * то параметр search-like='Mars' не присоединяется к урлу, сформированному стандартной функцией гриды
 * @todo Присоединять параметр serch и search-like в гридовом методе формирования пути
 */

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
        if (!is_null($categories_id) AND !is_null($products_id)) {
            // Связать продукт с категорией
            app()->dispatch('products', 'categories-to-products', ['categories_id' => $categories_id, 'products_id' => $products_id, 'operation' => 'add']);
        }
    }

    // Грида продуктов категории
    $grid_products = new \Core\Model\Products\SqlGrid($products_grid_options);
    $grid_products->setModule('products');
    $grid_products->setController('grid-categories-to-products');

    if ($gid == 'add-products') {
        if (isset($products_grid_options['search']))
            $grid_products->setLimit(100);
        else
            $grid_products->setLimit(5);
    } else
        $grid_products->setLimit(50);

    $view->grid_products = $grid_products;
    $view->gid = $gid;
    $view->categories_id = $categories_id;
    $view->catid = $catid;

};