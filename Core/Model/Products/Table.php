<?php


/**
 * @namespace
 */
namespace Application\Products;

/**
 * Table
 *
 * @category Application
 * @package  Products
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('products_id');

    /**
     * save test row - Example
     *
     * @return boolean
     */
    public function saveTestRow()
    {
        // return $this->insert(array('name'=>'Example #'.rand(1, 10), 'email'=> 'example@example.com'));
    }

    /**
     * update test row - Example
     *
     * @return boolean
     */
    public function updateTestRows()
    {
        // return $this->update(array('email'=> 'example2@example.com'), array('email'=> 'example@example.com'));
    }

    /**
     * delete test row  - Example
     *
     * @return boolean
     */
    public function deleteTestRows()
    {
        // return $this->delete(array('email'=> 'example2@example.com'));
    }

    function sortProducts(array $products, $order = 'desc')
    {
        $tmp = [];
        $t   = sizeof($products);
        for ($i = 0; $i < $t; $i++) {
            if ($order == 'desc')
                $index = $this->getMaxPriceProduct($products);
            else
                $index = $this->getMinPriceProduct($products);

            if(is_null($index))
                return $products;

            $tmp[] = $products[$index];
            unset($products[$index]);
        }

        return $tmp;
    }

    private function getMaxPriceProduct(array $products)
    {
        $index = null;

        $i = 0;

        $max = 0;

        foreach ($products as $ind => $product) {
            if(!isset($product['products_shoppingcart_price']))
                return null;

            if ($i == 0) {
                $max = $product['products_shoppingcart_price'];
                $i++;
                $index = $ind;
                continue;
            }

            if ($product['products_shoppingcart_price'] > $max) {
                $max   = $product['products_shoppingcart_price'];
                $index = $ind;
            }
        }

        return $index;
    }

    private function getMinPriceProduct(array $products)
    {
        $index = null;
        $i = 0;
        $min = 0;

        foreach ($products as $ind => $product) {
            if(!isset($product['products_shoppingcart_price']))
                return null;

            if ($i == 0) {
                $min = $product['products_shoppingcart_price'];
                $i++;
                $index = $ind;
                continue;
            }

            if ($product['products_shoppingcart_price'] < $min) {
                $min   = $product['products_shoppingcart_price'];
                $index = $ind;
            }
        }

        return $index;
    }

    /**
     * @param $products sting|array(0:id1,1:id2,...)
     * @return array
     * @throws \Exception
     */
    function getVendorsByProducts($products)
    {
        if(is_string($products)){
            $products_str = $products;
        } elseif(is_array($products)) {
            $products_str = implode(",", $products);
        } else {
            throw new \Exception(__CLASS__ . "::".__METHOD__.": "."Не верный тип данных");
        }

        $query = "
            SELECT m.manufacturers_id,m.manufacturers_name
            FROM {$this->table} p
            JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
            WHERE products_id IN ($products_str)
            GROUP BY m.manufacturers_id
            ORDER BY m.manufacturers_name
        ";

        $vendors = app()->getDb()->fetchAll($query);

        return $vendors;
    }

    /**
     * @param string|array $ids
     * @return array
     */
    public function readSetIn ( $ids )
    {
        if( is_array($ids) ){

            $ids = array_reduce(
                $ids,
                function ($e = '',$i){
                    $e .= $i . ",";
                    return $e;
                }
            );

            $ids = substr($ids,0,-1);
        }

        $query = "
            SELECT p.*
            FROM {$this->table} p
            WHERE p.products_id IN ($ids)
            ORDER BY p.products_name
        ";


        $result = app()->getDb()->fetchAll($query);

        return $result;
    }
}
