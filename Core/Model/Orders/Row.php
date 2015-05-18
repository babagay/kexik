<?php

namespace Application\Orders;

use Application\OrderProducts;
use Application\Exception;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;
//use Bluz\Crud\Table;
use Zend\Mvc\Application;


class Row extends \Bluz\Db\Row
{
    /**
     * __insert
     *
     * @return void
     */
    public function beforeInsert()
    {
        $this->date_added = gmdate('Y-m-d H:i:s');

    }

    function getData()
    {
        return $this->data;
    }

    function deleteProduct($products_id, $orders_id = null)
    {

        if(is_null($orders_id)) $orders_id = $this->orders_id;

        $row = new OrderProducts\Row(['products_id' => $products_id,'orders_id' => $orders_id]);
        $row->delete();


        $this->_refresh();

    }

    function addProduct($products_id, $orders_id = null)
    {
        if(is_null($orders_id)) $orders_id = $this->orders_id;

        $selectBuilder = app()->getDb()
            ->select('op.products_id')
            ->from('order_products', 'op')
            ->where("products_id = '$products_id' AND orders_id = '$orders_id'");
        $item = $selectBuilder->execute();

        if(sizeof($item)) throw new Exception("Продукт уже присоединен к заказу");

        $selectBuilder = app()->getDb()
            ->select('p.products_shoppingcart_price')
            ->from('products', 'p')
            ->where("products_id = '$products_id'");
        $product = $selectBuilder->execute();

        $row = new OrderProducts\Row();
        $row->orders_id = $orders_id;
        $row->products_id = $products_id;
        $row->price = $product[0]['products_shoppingcart_price'];
        $row->products_num = 1;
        $row->save();

        $this->_refresh();
    }

    /**
     * Обновить параметры продукта, например, количество единиц
     * @param $products_id
     * @param $orders_id
     * @param array $params
     */
    function updateProduct($products_id, array $params = [], $orders_id = null)
    {
        if(is_null($orders_id)) $orders_id = $this->orders_id;

        OrderProducts\Crud::getInstance()->updateOne(['orders_id' => $orders_id, 'products_id' => $products_id],$params);

        $this->_refresh();
    }

    function updateOrder( array $params = [], $orders_id = null)
    {
        if(is_null($orders_id)) $orders_id = $this->orders_id;

        $updateBuilder = app()->getDb()
            ->update('orders')
            ->setArray($params)
            ->where('orders_id = ?', $orders_id);
        $updateBuilder->execute();

        $this->_refresh();
    }

    /**
     * пересчитывает тотал с учетом скидки
     */
    function _refresh()
    {
        $total  = 0;

        $selectBuilder = app()->getDb()
            ->select('op.*')
            ->from('order_products', 'op')
            ->where("orders_id = '{$this->orders_id}'");
        $products = $selectBuilder->execute();

        if(sizeof($products)){
            $products_total = 0;
            foreach($products as $product){
                $products_total += $product['price'] * $product['products_num'];
            }
            $discount = $this->user_discount / 100;
            $total = $products_total - ($products_total * $discount);
        }

        Table::getInstance()->update(['total' => $total],['orders_id' => $this->orders_id]);

        parent::refresh();

    }

}
