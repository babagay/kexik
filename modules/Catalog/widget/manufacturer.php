<?php

namespace Application;

//use Application\Users\Table;

return
    /**
     * @return \closure
     */
    function ($категория, $manufacturers_id = null) {
        /**
         * @var \Application\Bootstrap $this
         */

        $view = app()->getView();
        $app_object = app()->getInstance();
        $db =  app()->getDb();

        $params = $app_object->getRequest()->getParams();

        $query = "  SELECT p2c.products_id
                    FROM products_to_categories p2c
                    WHERE categories_id = " . $db->quote($категория);

        $products = $db->fetchAll($query);

        $tmp = array();
        $products_str = '';

        foreach($products as $product){
            $tmp[] = $product['products_id'];
            $products_str .= $product['products_id'] . ",";
        }

        $products_str = substr($products_str,0,-1);

        $query = " SELECT p.manufacturers_id, m.manufacturers_id, m.manufacturers_name
                    FROM products_description p
                    JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
                    WHERE products_id IN ($products_str)
                    GROUP BY p.manufacturers_id
        ";


        $manufacturers = $db->fetchAll($query);

        $manufacturer_item_all = '';

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

        echo $html;
    };