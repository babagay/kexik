<?php

namespace Application;

use Bluz;
//use Application\Admin;
use Zend\Mvc\Application;

//use Zend\Permissions\Acl\Role\Registry;

$_this = $this;

return
    /**
     * @return \closure
     */
    function ($products_id = null) use ($view, $module, $controller, $_this) {
        /**
         * @var \Application\Bootstrap $this
         * @var \Bluz\View\View $view
         */
        /*
        $_this->getLayout()->breadCrumbs(
            array(
                $view->ahref('Test', array('test', 'index')),
                'Grid with SQL',
            )
        );
    */

        $options = array();

        ///$params = $_this->getRequest()->getParams();

        ///$get_params = $_this->getRequest()->get;


        ///$products_name_filter = '';

        /*
        if(isset($get_params['sql-filter-products_name'])){
            // полнотекстовый поиск
            $search = $get_params['search-like'];

            $options = array('search-like' => $search);
            $products_name_filter = $search;



        } elseif( isset($params['sql-filter-products_name']) ){
            // полнотекстовый поиск

            $search = substr($params['sql-filter-products_name'],5);
            $options = array('search-like' => $search);
            $products_name_filter = $search;
        }
        */

        $grid = new  \Application\Users\SqlGrid($options); //\Core\Model\Products\SqlGrid($options);
        $grid->setModule($module);
        $grid->setController($controller);


        //$allowed_filters = $grid->getAllowFilters();


        // just example of same custom param for build URL
        //$grid->setParams(array('products_id'=>$products_id));

        //fb($allowed_filters);


        ///if(isset($params['sql-limit'])) $grid->setLimit($params['sql-limit']);

        // Нужно дублировать этот массив здесь и в гриде в виде массива допустимых фильтров
        $filters = array(
            'id'    => 'Id',
            'login' => 'Логин',
            'email' => 'Адрес почты',
            'phone' => 'Телефон',
            'name'  => 'Имя',
            // 'status' => 'Статус',
        );

        $field = 'Выбрать поле';
        $value = '';
        $type  = $grid::FILTER_LIKE;

        $now_filters = $grid->getFilters();
        foreach ($now_filters as $column => $filter) {
            foreach ($filters as $allowed_filter => $title) {
                if ($allowed_filter == $column) {
                    $field = $filters[$column];

                    foreach ($filter as $filter_type => $filter_val) {
                        $value = $filter_val;
                        $type  = $filter_type;
                    }
                    break;
                }
            }
        }


        $view->grid    = $grid;
        $view->filters = $filters;
        $view->field   = $field;
        $view->value   = $value;
        $view->type    = $type;

        ///$view->products_name_filter = $products_name_filter;


        /*
            $emptyRows = 0;
            if ($grid->page() > 1 && sizeof($grid->getData()) < $grid->getLimit())
                $emptyRows = $grid->getLimit() - sizeof($grid->getData());

            $view->emptyRows = $emptyRows;


            $view->grid_order_1 = $grid->order('id');
            $view->grid_order_2 = $grid->order('name');
            $view->grid_order_3 = $grid->order('status');

            $view->grid_getLimit =  $grid->getLimit();
            $view->grid_limit_5 = $grid->limit(5);
            $view->grid_limit_25 = $grid->limit(25);
            $view->grid_limit_50 = $grid->limit(50);
            $view->grid_limit_100 = $grid->limit(100);

            $view->prev = $grid->prev();
            $view->next = $grid->next();

            $pages_link = array();
            for($i=1; $i<=$grid->pages(); $i++) {
                $pages_link[$i] = $grid->page( $i );
            }
            $view->pages_link = $pages_link;
            */

    };
