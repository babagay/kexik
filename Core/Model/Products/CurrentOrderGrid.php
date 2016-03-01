<?php
/**
 * Created by PhpStorm.
 * User: babagay
 * Date: 27.02.16
 * Time: 10:44
 *
 * TODO сортировка по вычисляемому полю products_num работает странно
 */

// namespace Bluz\Model\Products; // by default
namespace Core\Model\Products;


use Bluz\Grid\Grid;
use \Bluz\Grid\Source\ArraySource;
use Zend\Mvc\Application;
use \Application\Products\Table as ProductsTable;

class CurrentOrderGrid extends Grid
{
    protected $uid = 'CurrentOrderGrid';

    /**
     * init
     *
     * @return Grid
     */
    public function init()
    {
        $adapter = new ArraySource();

        $adapter->setSource( $this->getItems() );

        $this->setAdapter($adapter);
        $this->setDefaultLimit(100);
        $this->setAllowOrders(array('products_barcode', 'products_name', 'products_shoppingcart_price', 'products_quantity', 'products_num' ));
        $this->setAllowFilters(array('products_barcode', 'products_name','products_shoppingcart_price', 'products_quantity', 'products_num'));

        return $this;
    }

    /**
     * Формирует массив продуктов для гриды
     * @return array
     */
    private function getItems()
    {
        $products = [];

        $basketItems = app()->getBasket()->getItems();

        if( !sizeof($basketItems) ) return $products;

        $ids = $this->getIds();

        $productsTable =  ProductsTable::getInstance();
        $products = $productsTable->readSetIn($ids);

        for($i=0; $i<count($products); $i++){
            $products[$i]['products_num'] = $basketItems[$products[$i]['products_id']];
        }

        return $products;
    }

    /**
     * Возвращает айдишники продуктов, взятых из корзины, слерденные в строку
     * @return string
     */
    private function getIds (  )
    {
        return substr(
            array_reduce(
            array_keys( app()->getBasket()->getItems() ),
            function ($e = '',$i){
                $e .= $i . ",";
                return $e;
            }
        ),0,-1);
    }

    function __toString(){
        return 'CurrentOrderGrid-class';
    }
}