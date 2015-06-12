<?php

/**
 * @namespace
 */
namespace Application\Filters;

use Application\Exception;
use Bluz\Application;

/**
 * Class Table
 * @package Application\Filters
 *
 */

class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'filters';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('filters_id');

    function getFiltersByProducts($products, $exact = true)
    {
       if(!is_string($products)){
           if( isset($products[0]) ){
               $products_str = "";
               foreach($products as $product){
                   $products_str .= $product['products_id'] . ",";
               }
               if(substr($products_str,-1) == ",")
                   $products_str = substr_replace($products_str,"",-1);
               $products = $products_str;
           } elseif( is_array($products) ){
               $products_str = implode(",",$products);
               $products = $products_str;
           }
       }

        if ($exact AND trim($products) == '')
            return [];

        if (trim($products) != '')
            $products_condition = "WHERE f2p.products_id IN($products)";
        else
            $products_condition = "WHERE f2p.products_id = '-1' ";

        $query_f = "SELECT f.*
                          FROM filters_to_products f2p
                          LEFT JOIN filters f ON f.filters_id = f2p.filters_id
                          $products_condition
                           GROUP BY f2p.filters_id";

        $filters = app()->getDb()->fetchAll($query_f);

        return $filters;
   }

    function getFiltersByIds(array $ids)
    {
        $filters_ids_str = implode(",",$ids);
        $query = "SELECT f.*
                      FROM filters f
                      WHERE filters_id IN($filters_ids_str)
                     ";

        $filters = app()->getDb()->fetchAll($query);

        return $filters;
    }

    function getFilters()
    {
        $cacheKey = 'filters:all:';

        if (!$data = app()->getCache()->get($cacheKey)) {

            $query = "SELECT f.*
                      FROM filters f
                     ";

            $filters = app()->getDb()->fetchAll($query);

            $data = [];
            if (sizeof($filters)) {
                foreach ($filters as $id => $filter) {
                    $data[$filter['filters_id']] = $filter['name'];
                }
            }

            app()->getCache()->set($cacheKey, $data, \Bluz\Cache\Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'filters');

        }

        return $data;
    }

    function filterProducts(array $products, array $filters)
    {
        $_str = implode(",", $filters);

        // Взять все продукты, привязанные к данным фильтрам
        $products_by_filter = array();

        $query = "SELECT products_id
                              FROM filters_to_products
                              WHERE filters_id IN($_str)   ";

        $products_filtered_tmp = app()->getDb()->fetchAll($query);

        if (is_array($products_filtered_tmp))
            foreach ($products_filtered_tmp as $products_filtered_tmp_item) {
                $products_by_filter[$products_filtered_tmp_item['products_id']] = $products_filtered_tmp_item['products_id'];
            }


        // Вычислить пересечение массивов
        $tmp = [];
        if (sizeof($products_by_filter) AND sizeof($products)) {
            foreach ($products as $product) {
                foreach ($products_by_filter as $filtered_id => $product_filtered_id) {
                    if ($product['products_id'] == $product_filtered_id) {
                        $tmp[] = $product;
                        continue;
                    }
                }
            }
        } else {
            $products = [];
        }

        /* ПРимер
        if(sizeof($subcat_products) AND sizeof($products)){
            foreach($subcat_products as $subcat_product){
                foreach($products as $product){
                    if($product['products_id'] == $subcat_product['products_id']){
                        $tmp[] = $product;
                    }
                }
            }
            $products = $tmp;
        }
        */

        return $tmp;
    }

}
