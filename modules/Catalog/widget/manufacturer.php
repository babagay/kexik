<?php

namespace Application;

//use Application\Users\Table;

return
    /**
     * @return \closure
     * Для корректной работы нужно задать либо $категория (categories_id), либо $продукты (array of products_id)
     * Всегда нужно передавать все параметры
     */
    function ($категория = null, $manufacturers_id = null, $продукты = null) {
        /**
         * @var \Application\Bootstrap $this
         */

        $view = app()->getView();
        $app_object = app()->getInstance();
        $db =  app()->getDb();

        $manufacturer_item_all = '';
        $tmp = $products = array();
        $products_str = '';

        $params = $app_object->getRequest()->getParams();

        $sub_categories = array();


        if(!is_null($категория)){
            $query = "  SELECT p2c.products_id
                        FROM products_to_categories p2c
                        WHERE categories_id = " . $db->quote($категория);

            $products = $db->fetchAll($query);

            foreach($products as $product){
                $tmp[] = $product['products_id'];
                $products_str .= $product['products_id'] . ",";
            }

            $products_str = substr($products_str,0,-1);

            $query = "  SELECT  c.*
                        FROM categories c
                        WHERE parent_id = " . $db->quote($категория) . "
                        AND categories_level = ". $db->quote(2);
            $sub_categories = $db->fetchAll($query);

            //fb($products[0]['products_id'] = products_id);

        } elseif (!is_null($продукты)){
            /**
             * Сюда входит через поиск
             */

            $products = $продукты; // //fb($products[0] = products_id);
            $products_str = implode(",",$продукты);

            $query = " SELECT *
                       FROM categories c
                       WHERE categories_level = ". $db->quote(2) . "
                       AND parent_id IN(
                            SELECT categories_id
                           FROM products_to_categories
                           WHERE products_id IN ($products_str)
                           )
                        ";

            $sub_categories = $db->fetchAll($query);
        }

        $query = " SELECT p.manufacturers_id, m.manufacturers_id, m.manufacturers_name
                    FROM products p
                    JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
                    WHERE products_id IN ($products_str)
                    GROUP BY p.manufacturers_id
        ";

        $manufacturers = $db->fetchAll($query);


        $manufacturer_name = 'Производитель:';
        if($manufacturers_id !== null){
            foreach($manufacturers as $vendor){
                if($vendor['manufacturers_id'] == $manufacturers_id){
                    $manufacturer_name = $vendor['manufacturers_name'];
                    $manufacturer_item_all = '<li><a class="filter-vendor" categories_id="'.$категория.'" manufacturers_id="all" href="'.$view->baseUrl('catalog/products') .'">Все</a></li>';
                    break;
                }
            }
        }

        $html = '<div class="position-relative">
                    <div class="select-cabnet">
                        <span id="filter_manufacturer_name">'.$manufacturer_name.'</span>
                        <ul class="select-no-display">
                       ';

        foreach($manufacturers as $manufacturer){ ///Фрукты/ягоды/'.$manufacturer['manufacturers_id']
            $html .= ' <li><a class="filter-vendor" categories_id="'.$категория.'" manufacturers_id="'.$manufacturer['manufacturers_id'].'" href="'.$view->baseUrl('catalog/products') .'">'.$manufacturer['manufacturers_name'].'</a></li>';
        }

        $html .= $manufacturer_item_all;

        $html .= '    </ul>
                    </div>
                </div>';

        ///$("#filter_manufacturer_name").html('aиьтиьтиsd1ти')

        // Показать субкатегории
        if(isset($sub_categories[0])){

            foreach($sub_categories as $sub_category){
                $checked = false;
                $html .= "<div>" . $view->checkbox($sub_category['categories_id'],"on",$checked,["data-name" => "asd", "class" => "filter-subcategory"]) . " " . $sub_category['categories_name'] . "</div>" ;
            }

        }

        // Показать фильтры
        $query = "SELECT f.*
                  FROM filters_to_products f2p
                  LEFT JOIN filters f ON f.filters_id = f2p.filters_id
                  WHERE f2p.products_id IN($products_str)
                   GROUP BY  f2p.filters_id";

        $filters = $db->fetchAll($query);

        if(isset($filters[0])){
            foreach($filters as $filter){
                $checked = false;
                $html .= "<div>" . $view->checkbox($filter['key'],"on",$checked,["data-name" => "asd", "class" => "filter-origin"]) . " " . $filter['name'] . "</div>" ;
            }
        }




        echo $html;
    };