<?php
return
    /**
     * @param integer $orders_id
     * @param string $gid - дополнительный условный идентификатор гриды
     * @param integer $products_id
     * @param array $params
     * @return \closure
     * @privilege Edit
     */
    function ($orders_id = null, $gid = null, $products_id = null, $params = null) use ($view) {
        $products_grid_options = array();

        if (!is_null($orders_id)) {
            if ($gid == 'order-products')
                $products_grid_options['orders_id'] = $orders_id;
        }

        $search = null;

        $params = app()->getRequest()->getParams();
        $get_params = app()->getRequest()->get;

        if (isset($get_params['gid'])) {
            if (is_null($gid))
                $gid = $get_params['gid'];
        }

        if (isset($get_params['products_id'])) {
            if (is_null($products_id))
                $products_id = $get_params['products_id'];
        }

        # Body
        if (app()->getRequest()->getMethod() == 'DELETE') {
            if (!is_null($orders_id) AND !is_null($products_id)) {
                // Удалить продукт из заказа
                app()->dispatch('orders', 'edit', ['orders_id' => $orders_id, 'products_id' => $products_id, 'operation' => 'delete']);
            }
        }

        if (app()->getRequest()->getMethod() == 'ADD') {
            if (!is_null($orders_id) AND !is_null($products_id)) {
                // Добавить продукт
                app()->dispatch('orders', 'edit', ['orders_id' => $orders_id, 'products_id' => $products_id, 'operation' => 'add']);
            }
        }

        if (app()->getRequest()->getMethod() == 'POST') {
            if (!is_null($orders_id) AND !is_null($products_id)) {
                // Изменить продукт
                app()->dispatch('orders', 'edit', ['orders_id' => $orders_id, 'products_id' => $products_id, 'operation' => 'update', 'params' => $params]);
            }
        }

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

        // Грида продуктов ордера/грида добавления продуктов
        $grid_products = new \Core\Model\Products\SqlGrid($products_grid_options);
        $grid_products->setModule(app()->getRequest()->getModule());
        $grid_products->setController('grid-products');

        //$grid_products->setController(app()->getRequest()->getController());
        //if(isset($params['sql-limit'])) $grid_products->setLimit($params['sql-limit']);

        if ($gid == 'add-products') {
            if (isset($products_grid_options['search']))
                $grid_products->setLimit(100);
            else
                $grid_products->setLimit(5);
        } else
            $grid_products->setLimit(50);

        $view->orders_id = $orders_id;
        $view->grid_products = $grid_products;
        $view->gid       = $gid;

        return 'grid-products.phtml';
    };
