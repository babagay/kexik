<?php
return
    /**
     * @param integer $orders_id
     * @return \closure
     * @privilege Edit
     */
    function ($orders_id = null) use ($view) {

        $products_grid_options = array();


        if(!is_null($orders_id)) {
            $products_grid_options['orders_id'] = $orders_id;
        }

        $search = null;

        $params = app()->getRequest()->getParams()  ;
        $get_params = app()->getRequest()->get;

        if(is_array($get_params)){
            foreach($get_params as $get_param => $val){
                if( $pos =   strpos($get_param, 'filter-products_id' )  ){
                    $products_grid_options['search-column'] = 'products_id';
                } elseif( $pos =   strpos($get_param, 'filter-products_name' ) ){
                    $products_grid_options['search-column'] = 'products_name';
                } elseif( $pos =   strpos($get_param, 'filter-products_barcode' ) ){
                    $products_grid_options['search-column'] = 'barcode';
                }
            }
        }

        if(isset($get_params['search-like'])){
            if(trim($get_params['search-like']) != ''){
                $search = $get_params['search-like'];
                $products_grid_options['search'] = $search;
            }
        }

        // Грида продуктов ордера
        $grid_products = new \Core\Model\Products\SqlGrid($products_grid_options);
        $grid_products->setModule(app()->getRequest()->getModule() );
        $grid_products->setController('grid-products'); //$grid_products->setController(app()->getRequest()->getController());
        //if(isset($params['sql-limit'])) $grid_products->setLimit($params['sql-limit']);
        $grid_products->setLimit(500);



        $view->orders_id = $orders_id;
        $view->grid_products = $grid_products;

        return 'grid-products.phtml';
    };
