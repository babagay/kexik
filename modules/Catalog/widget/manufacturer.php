<?php

namespace Application;

//use Application\Users\Table;

use Awakenweb\Livedocx\tests\units\Livedocx;
use Bluz\Cache\Cache;
use Bluz\Crud\Table;
use Zend\Mvc\Application;

return
    /**
     * @return \closure
     * Для корректной работы нужно задать либо $категория (categories_id), либо $продукты (array of products_id)
     * При вызове из phtml-шаблона Всегда нужно передавать все параметры
     * $фильтры - массив отмеченных в данный момент фильтров
     */
    function ($категория = null, $manufacturers_id = null, $продукты = null, $порядок_сортировки = null, $фильтры = null, $поисковая_фраза = null) {
        /**
         * @var \Application\Bootstrap $this
         */

        $view       = app()->getView();
        $app_object = app()->getInstance();
        $db         = app()->getDb();

        $manufacturer_item_all = '';
        $tmp                   = $products = array();
        $products_str          = '';

        $params = $app_object->getRequest()->getParams();

        $sub_categories = array();

        $filters      = null;
        $filterKeeper = app()->getFilterKeeper();

        if (!is_null($продукты)) {
            /**
             * Передаем непосредственно массив products_id
             * Сюда входит либо через поиск, либо через фильтры
             */

            $products     = $продукты; // //fb($products[0] = products_id);
            $products_str = implode(",", $продукты);

            $query = " SELECT *
                       FROM categories c
                       WHERE categories_level = " . $db->quote(2) . "
                       AND parent_id IN(
                            SELECT categories_id
                           FROM products_to_categories
                           WHERE products_id IN ($products_str)
                           )
                        ";

            $sub_categories = $db->fetchAll($query);

        } elseif (!is_null($категория)) {
            $query = "  SELECT p2c.products_id
                        FROM products_to_categories p2c, products p
                        WHERE p.products_id = p2c.products_id AND p.products_visibility = 1
                                AND categories_id = " . $db->quote($категория);

            $products = $db->fetchAll($query);

            foreach ($products as $product) {
                $tmp[] = $product['products_id'];
                $products_str .= $product['products_id'] . ",";
            }

            $products_str = substr($products_str, 0, -1);

            $query          = "  SELECT  c.*
                        FROM categories c
                        WHERE parent_id = " . $db->quote($категория) . "
                        AND categories_level = " . $db->quote(2);
            $sub_categories = $db->fetchAll($query);

            //fb($products[0]['products_id'] = products_id);

        }

        $query = " SELECT p.manufacturers_id, m.manufacturers_id, m.manufacturers_name
                    FROM products p
                    JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
                    WHERE products_id IN ($products_str)
                    GROUP BY p.manufacturers_id
                    ORDER BY m.manufacturers_name
        ";

        $manufacturers = $db->fetchAll($query);


        $manufacturer_name = 'Производитель:';
        if ($manufacturers_id !== null) {
            foreach ($manufacturers as $vendor) {
                if ($vendor['manufacturers_id'] == $manufacturers_id) {
                    $manufacturer_name     = $vendor['manufacturers_name'];
                    $manufacturer_item_all = '<li><a class="filter-vendor" categories_id="' . $категория . '" manufacturers_id="all" href="catalog/products">Все</a></li>';
                    break;
                }
            }
        }

        // Фильтр по сортировке цены
        $sort_order_current = "Цена:";
        if (!is_null($порядок_сортировки)) {
            if (preg_match('/products_shoppingcart_price/', $порядок_сортировки)) {
                if (preg_match('/asc/', $порядок_сортировки)) $sort_order_current = "по возрастанию";
                elseif (preg_match('/desc/', $порядок_сортировки)) $sort_order_current = "по убыванию";
            }
        }

        $html = '<div class="position-relative">
                    <div class="select-cabnet"><span> ' . $sort_order_current . '   </span>
                        <ul class="select-no-display">
                            <li><a href="catalog/products" direction="asc" class="filter-price-order">по возрастанию </a></li>
                            <li><a href="catalog/products" direction="desc" class="filter-price-order">по убыванию</a></li>
                        </ul>
                    </div>
                </div>';

        $html .= '<div class="position-relative">
                    <div class="select-cabnet">
                        <span id="filter_manufacturer_name">' . $manufacturer_name . '</span>
                        <ul class="select-no-display">
                       ';

        foreach ($manufacturers as $manufacturer) { ///Фрукты/ягоды/'.$manufacturer['manufacturers_id']
            $html .= ' <li><a class="filter-vendor" categories_id="' . $категория . '" manufacturers_id="' . $manufacturer['manufacturers_id'] . '" href="catalog/products">' . $manufacturer['manufacturers_name'] . '</a></li>';
        }

        $html .= $manufacturer_item_all;

        $html .= '    </ul>
                    </div>
                </div>';

        ///$("#filter_manufacturer_name").html('aиьтиьтиsd1ти')

        $html .= "<div class=\"checkboxes\">";

        // Показать субкатегории
        if (isset($sub_categories[0])) {

            foreach ($sub_categories as $sub_category) {
                $checked = false;
                $html .= " <div>" . $view->checkbox($sub_category['categories_id'], "on", $checked, ["data-name" => "asd", "class" => "filter-subcategory"]) . "<label> " . $sub_category['categories_name'] . "</label></div>";
            }

        }


        /*
        //if(!is_null($фильтры)){
        if(false){
            //FIXME зачем нужен этот блок
            $filters_ids_str = implode(",",$фильтры['filter_origin']);

            $query = "SELECT f.*
                      FROM filters f
                      WHERE filters_id IN($filters_ids_str)
                     ";

            $filters = $db->fetchAll($query);

        }
        */

        // Берем оригинальные фильтры
        // - по поисковой фразе
        // - по  категории и вендору
        // - по категории
        if (!is_null($поисковая_фраза)) {

            if ($фильтры_по_фразе = $filterKeeper->setPhrase($поисковая_фраза)->selectFilterType("origin")->getFilters()) {
                $filters = $фильтры_по_фразе;
            } else {
                $filters = Filters\Table::getInstance()->getFiltersByProducts($products_str);
                $filterKeeper->setFiltersOrigin($filters);
            }

        } elseif (!is_null($manufacturers_id) AND !is_null($категория)) {
            // получить фильтры для вендора внутри категории

            if ($фильтры_вендора = $filterKeeper->setVendor($manufacturers_id, $категория)->selectFilterType("origin")->getFilters()) {
                $filters = $фильтры_вендора;
            } else {
                $filters = Filters\Table::getInstance()->getFiltersByProducts($products_str);
                $filterKeeper->setFiltersOrigin($filters);
            }

        } elseif (!is_null($категория)) {
            // Берем фильтры для категории

            if ($фильтры_категории = $filterKeeper->clearContext()->selectContext("category", $категория)->selectFilterType("origin")->getFilters()) {
                $filters = $фильтры_категории;
            } else {
                $filters = Filters\Table::getInstance()->getFiltersByProducts($products_str);
                $filterKeeper->setFiltersOrigin($filters);
            }
        }


        if (isset($filters[0])) {
            foreach ($filters as $filter) {
                $checked = false;

                if (is_array($фильтры)) {

                    if (isset($фильтры['filter_origin'])) {
                        //$фильтры['filter_origin'] = array_unique($фильтры['filter_origin']);
                        foreach ($фильтры['filter_origin'] as $id => $_filter) {
                            if ((int)$_filter == (int)$filter['filters_id']) {
                                $checked = true;
                                break;
                            }
                        }
                    }
                }


                $html .= ' <div>' . $view->checkbox($filter['key'], "on", $checked, ["data-id" => $filter['filters_id'], "class" => "filter-origin"]) . " <label>" . $filter['name'] . "</label></div>";
            }
        }


        $html .= "</div>";

        echo $html;
    };