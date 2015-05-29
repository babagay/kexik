<?php

/**
 * @namespace
 */
namespace Application\FiltersToProducts;

use Application\Exception;
use Bluz\Application;

/**
 * Class Table
 * @package Application\FiltersToProducts
 *
 */

class Table extends \Bluz\Db\Table
{


    /**
     * Table
     *
     * @var string
     */
    protected $table = 'filters_to_products';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('products_id', 'filters_id');


    function getProducts(array $filters)
    {

    }

    function getFilters(array $products)
    {
        if (!sizeof($products)) return false;

        $str   = implode(",", $products);
        $query = "SELECT filters_id
                      FROM {$this->table}
                      WHERE products_id IN($str)
                     ";

        $filters = app()->getDb()->fetchAll($query);

        if (true) {
            if (sizeof($filters)) {
                $tmp = [];
                foreach ($filters as $filter) {
                    $tmp[] = $filter['filters_id'];
                }
                $filters = $tmp;
            }
        }

        return $filters;

        /*
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

        $query_f = "SELECT f.*
                       FROM filters_to_products f2p
                       LEFT JOIN filters f ON f.filters_id = f2p.filters_id
                       WHERE f2p.products_id IN($products)
                        GROUP BY  f2p.filters_id";


        $filters = app()->getDb()->fetchAll($query_f);

        return $filters;
        */
    }


}
