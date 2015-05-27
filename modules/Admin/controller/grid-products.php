<?php

namespace Application;

use Bluz;
use Application\Admin;

//use Zend\Permissions\Acl\Role\Registry;

$_this = $this;

return
    /**
     * @return \closure
     */
    function ($products_id = null, $operation = null) use ($view, $module, $controller, $_this) {
        /**
         * @var \Application\Bootstrap $this
         * @var \Bluz\View\View $view
         */


        $options = array();

        $params = $_this->getRequest()->getParams();

        $get_params = $_this->getRequest()->get;
        //fb($_this->getRequest()->getRawParams() );
        $products_name_filter = '';

        # Скрыть продукт
        if (!is_null($operation) AND !is_null($products_id)) {
            if ($operation == 'unhide') $operation = 1; else $operation = 0;
            Products\Table::update(['products_visibility' => $operation], array('products_id' => $products_id));
        }

        if (isset($get_params['sql-filter-products_name'])) {
            // полнотекстовый поиск
            $search = $get_params['search-like'];

            $options              = array('search-like' => $search);
            $products_name_filter = $search;


        } elseif (isset($params['sql-filter-products_name'])) {
            // полнотекстовый поиск

            $search               = substr($params['sql-filter-products_name'], 5);
            $options              = array('search-like' => $search);
            $products_name_filter = $search;
        }

        $grid = new \Core\Model\Products\SqlGrid($options);
        $grid->setModule($module);
        $grid->setController($controller);


        // just example of same custom param for build URL
        //$grid->setParams(array('products_id'=>$products_id));

        //fb($products_id);


        if (isset($params['sql-limit'])) $grid->setLimit($params['sql-limit']);


        $filters = array(
            'products_id'      => 'Артикул',
            'products_barcode' => 'Штрихкод',
            'products_name'    => 'Наименование',
        );

        $default_filter = ['key' => 'products_name', 'title' => 'Наименование'];

        $field = $default_filter['title']; //'Выбрать поле';
        $value = '';
        $type  = $grid::FILTER_LIKE;

        $now_filters = $grid->getFilters();
        foreach ($now_filters as $column => $filter) {
            foreach ($filters as $allowed_filter => $title) {
                if ($allowed_filter == $column) {
                    $field = $filters[$column];

                    foreach ($filter as $filter_type => $filter_val) {

                        $value = str_replace("fulltext-", "", $filter_val);

                        $type = $filter_type;
                    }
                    break;
                }
            }
        }

        $view->default_filter = $default_filter;
        $view->field          = $field;
        $view->value          = Bluz\Translator\Translator::translit($value);
        $view->type           = $type;
        $view->filters        = $filters;

        $view->grid = $grid;

        $view->products_name_filter = $products_name_filter;

        //$view->operation_hide = $operation_hide;

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
