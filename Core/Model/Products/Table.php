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
}
