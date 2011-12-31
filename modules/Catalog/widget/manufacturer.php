<?php

namespace Application;

//use Application\Users\Table;

//use Awakenweb\Livedocx\tests\units\Livedocx;
use Bluz\Cache\Cache;
use Bluz\Crud\Table;

//use Zend\Mvc\Application;

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
            /*
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
            */

        } elseif (!is_null($категория)) {

            // FIXME нужен ли здесь этот запрос
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

            /*
            $query          = "  SELECT  c.*
                        FROM categories c
                        WHERE parent_id = " . $db->quote($категория) . "
                        AND categories_level = " . $db->quote(2);
            $sub_categories = $db->fetchAll($query);
            */

            //fb($products[0]['products_id'] = products_id);

        }

        if (!is_null($поисковая_фраза)) {
            if ($sub_categories = $filterKeeper->clearContext()->setPhrase($поисковая_фраза)->selectFilterType("by_category")->getFilters()) {
            } else {
                $sub_categories = Categories\Table::getInstance()->getCategoriesByProducts($продукты);
                $filterKeeper->setFiltersSubcategory($sub_categories);
            }
        } elseif (!is_null($manufacturers_id) AND !is_null($категория) AND (!is_null($продукты) AND sizeof($продукты))) {
            if ($sub_categories = $filterKeeper->clearContext()->setVendor($manufacturers_id, $категория)->selectFilterType("by_category")->getFilters()) {
            } else {
                // FIXME Для Борзны сюда не заходит повторно
                // Для производителей без фильтров - заходит каждый раз
                $sub_categories = Categories\Table::getInstance()->getCategoriesByProducts($продукты);
                $filterKeeper->setFiltersSubcategory($sub_categories);
            }
        } elseif (!is_null($категория)) {
            if ($sub_categories = $filterKeeper->clearContext()->selectContext("category", $категория)->selectFilterType("by_category")->getFilters()) {
            } else {
                // FIXME Для Борзны сюда не заходит повторно
                // Для производителей без фильтров - заходит каждый раз
                $sub_categories = Categories\Table::getInstance()->getChildren($категория);
                $filterKeeper->setFiltersSubcategory($sub_categories);
            }
        }

        // закешировать производителей
        $vendors = [];
        if (!is_null($поисковая_фраза)) {
            // при перезагрузке страницы поиска
            if( $vendors = $filterKeeper->clearContext()->selectContext('phrase')->setPhrase($поисковая_фраза)->selectFilterType('manufacturer')->getFilters() ){
            } else {
                // создать списко вендоров для поисковой фразы, т.е. для данного списка товаров
                $vendors = Products\Table::getInstance()->getVendorsByProducts($products_str);
                $filterKeeper->setFiltersVendor($vendors);
            }
        } elseif (!is_null($manufacturers_id) AND !is_null($категория) AND (!is_null($продукты) AND sizeof($продукты))) {
            // при выборе производителя аяксом
            if( $vendors = $filterKeeper->clearContext()->selectContext("category_to_vendors", $категория)->selectFilterType("manufacturer")->getFilters() ){
            } else {
                $vendors = Manufacturers\Table::getInstance()->getVendorsByCategory($категория);
                $filterKeeper->setFiltersVendor($vendors);
            }
        } elseif (!is_null($категория)) {
            // сюда входит при перезагрузке страницы каталога
            if ($vendors = $filterKeeper->clearContext()->selectContext("category_to_vendors", $категория)->selectFilterType("manufacturer")->getFilters()) {
            } else {
                $vendors = Manufacturers\Table::getInstance()->getVendorsByCategory($категория);
                $filterKeeper->setFiltersVendor($vendors);
            }
        }

        /*
        if(trim($products_str) != '') $products_condition = "WHERE products_id IN ($products_str)";
        else $products_condition = '';

        $query         = " SELECT p.manufacturers_id, m.manufacturers_id, m.manufacturers_name
                    FROM products p
                    JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
                    $products_condition
                    GROUP BY p.manufacturers_id
                    ORDER BY m.manufacturers_name
        ";
        $manufacturers = $db->fetchAll($query);
        */

        $manufacturer_name = 'Производитель:';
        //if ($manufacturers_id !== null) {
        if (sizeof($vendors)) {
            foreach ($vendors as $vendor) {
                if ($vendor['manufacturers_id'] == $manufacturers_id) {
                    $manufacturer_name     = $vendor['manufacturers_name'];
                    break;
                }
            }
            $manufacturer_item_all = '<li><a class="filter-vendor" categories_id="' . $категория . '" manufacturers_id="all" href="catalog/products">Все</a></li>';
        }
        // }

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

        $html .= $manufacturer_item_all;

        foreach ($vendors as $manufacturer) { ///Фрукты/ягоды/'.$manufacturer['manufacturers_id']
            $html .= ' <li><a class="filter-vendor" categories_id="' . $категория . '" manufacturers_id="' . $manufacturer['manufacturers_id'] . '" href="catalog/products">' . $manufacturer['manufacturers_name'] . '</a></li>';
        }

        $html .= '    </ul>
                    </div>
                </div>';

        ///$("#filter_manufacturer_name").html('aиьтиьтиsd1ти')

        $html .= "<form role=\"form\" class=\"form-inline\">";

        //  $html .= "<div class=\"checkboxes\">";

        // Показать субкатегории
        if (isset($sub_categories[0])) {

            foreach ($sub_categories as $sub_category) {
                $checked = false;

                if (is_array($фильтры)) {
                    if (isset($фильтры['filter_subcategory'])) {
                        //$фильтры['filter_origin'] = array_unique($фильтры['filter_origin']);
                        foreach ($фильтры['filter_subcategory'] as $fs_id => $fs_filter) {
                            if ((int)$fs_filter == (int)$sub_category['categories_id']) {
                                $checked = true;
                                break;
                            }
                        }
                    }
                }

                $html .= ' <div class="checkbox">' .
                    "<label> " .
                    $view->checkbox("subcategory-filter", "on", $checked, ["data-id" => $sub_category['categories_id'], "class" => "filter-subcategory"]) .
                    '<span class="label-filters-subcat">' . $sub_category['categories_name'] . '</span>' .
                    "</label>" .
                    "</div>";
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

        } elseif (!is_null($manufacturers_id) AND !is_null($категория) AND (!is_null($продукты) AND sizeof($продукты))) {
            // получить оригинальные фильтры для вендора внутри категории

            if ($фильтры_вендора = $filterKeeper->setVendor($manufacturers_id, $категория)->selectFilterType("origin")->getFilters()) {
                $filters = $фильтры_вендора;
            } else {
                // FIXME
                // Выбираем Вендора, у которого нет Овсяного печенья
                // Ставим галочку yf Jdczyjt gtxtymt - заходдит сюда каждый раз
                // Снимаем - не заходит
                /// fb('origin Mn'.'CREA');


                $filters = Filters\Table::getInstance()->getFiltersByProducts($products_str);
                $filterKeeper->setFiltersOrigin($filters);
            }

        } elseif (!is_null($категория)) {
            // Берем фильтры для категории

            if ($фильтры_категории = $filterKeeper->clearContext()->selectContext("category", $категория)->selectFilterType("origin")->getFilters()) {
                $filters = $фильтры_категории;
            } else {
                // FIXME Для Борзны сюда не заходит повторно
                // Для производителей без фильтров - заходит каждый раз
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

                $html .= ' <div class="checkbox">' .
                    "<label>" .
                    $view->checkbox($filter['key'], "on", $checked, ["data-id" => $filter['filters_id'], "class" => "filter-origin"]) .
                    '<span class="label-filters-origin">' . $filter['name'] . '</span>' .
                    "</label>" .
                    "</div>";
            }
        }

        // $html .= "</div>";

        $html .= "</form>";

        echo $html;

    };