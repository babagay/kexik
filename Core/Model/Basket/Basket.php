<?php
/**
 * Created by PhpStorm.
 * User: babagay
 * Date: 26.02.16
 * Time: 19:28
 *
 *
 */


namespace Zoqa\Basket;

use \Bluz\Basket\BasketInterface;
use \Bluz\Common\Singleton;
use \Bluz\Application\Exception\ApplicationException;
use Application\Products\Crud as Product;



final class Basket implements BasketInterface
{
    use Singleton;

    function flush(){
        unset( app()->getSession()->basket );
    }

    /**
     * Загрузка продуктов в корзину в виде пар products_id:products_num
     * @param array $products
     * @return mixed|void
     * @throws ApplicationException
     */
    public function set(array $products)
    {
        if( sizeof($products) == 0 )
            throw new ApplicationException("Ни один продукт не выбран");

        $basket = app()->getSession()->basket;

        if( isset($products[0]['products_id']) AND isset($products[0]['products_num']) ){
            foreach ($products as $item)
                $basket['products'][$item['products_id']] = $item['products_num'];

        } else {

            foreach ($products as $products_id => $num) {
                $basket[ 'products' ][ $products_id ] = $num;
            }
        }

        app()->getSession()->basket = $basket;

        unset($basket);
    }

    /**
     * @param int $products_id
     * @param float $products_num
     * @return mixed|void
     * @throws ApplicationException
     */
    public function updateProduct($products_id, $products_num)
    {
        $basket = app()->getSession()->basket;

        if( $products_id < 1 OR $products_num < Product::$minProductNum )
            throw new ApplicationException("product is not set");

        if($products_num == 0){
            $this->removeProduct($products_id);
        } else {
            $basket['products'][$products_id] = $products_num;
            app()->getSession()->basket = $basket;
        }

        unset($basket);
    }

    /**
     * @param $products_id
     * @return mixed|void
     * @throws ApplicationException
     */
    public function removeProduct($products_id)
    {
        $basket = app()->getSession()->basket;

        if( $products_id < 1 )
            throw new ApplicationException("Продукт не выбран");

        if(isset($basket['products'][$products_id]))
            unset($basket['products'][$products_id]);

        app()->getSession()->basket = $basket;

        unset($basket);
    }

    /**
     * @param $products_id
     * @param $products_num
     * @return mixed|void
     * @throws ApplicationException
     */
    public function putProduct($products_id, $products_num = null)
    {
        if( is_null($products_num) ) $products_num = 1;

        $basket = app()->getSession()->basket;

        if( is_null($basket) ){
            $basket = array();
        }

        if(isset($basket['products'][$products_id])) {
            if ( $basket[ 'products' ][ $products_id ] == $products_num ) {
                unset($basket);
                throw new ApplicationException("Продукт уже в корзине");
            }
        }

        $basket['products'][$products_id] = $products_num;

        app()->getSession()->basket = $basket;

        unset($basket);
    }

    /**
     * Вернуть массив элементов корзины
     * @return mixed
     */
    public function getItems()
    {
        $basket = app()->getSession()->basket;

        if(isset($basket['products']))
            return $basket['products'];

        return null;
    }
}