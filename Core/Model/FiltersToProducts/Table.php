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

    function insertFilters(array $data)
    {
        if (isset($data['filters_id']) AND isset($data['products_id'])) {
            foreach ($data['filters_id'] as $filter) {
                foreach ($data['products_id'] as $product) {
                    $row = new Row(['filters_id' => $filter, 'products_id' => $product]);
                    $row->save();
                }
            }
        }
    }

    function dropFilters(array $data)
    {
        if (isset($data['filters_id']) AND isset($data['products_id'])) {
            //TODO
            foreach ($data['filters_id'] as $filter) {
                foreach ($data['products_id'] as $product) {
                    fb($filter);
                    fb($product);
                    //$this->delete(['filters_id' => $filter, ])
                }
            }
        } elseif (isset($data['filters_id'])) {
            //TODO
        } elseif (isset($data['products_id'])) {

            if (is_array($data['products_id']))
                $str = implode(",", $data['products_id']);
            else
                $str = (int)$data['products_id'];

            return app()->getDb()->query("DELETE FROM {$this->table} WHERE products_id IN($str)");

            //return $this->delete($data['products_id']);
        }
    }


}
