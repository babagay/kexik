<?php


//namespace Core\Model\Orders;
namespace Application\Orders;

use Application\Auth;
use Application\Users;
use Application\Exception;

use Application\UsersActions;
use Bluz\Application\Application;
use Bluz\Crud\ValidationException;


class Crud extends \Bluz\Crud\Table
{

    private $products = null;
    private $user = null;
    private $total = null;

    private $exception;

    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    function createSet($data)
    {
         throw new \Exception("Not Implemented " . __METHOD__ . " in " . __FILE__);
    }

    /**
     * @param array $data
     * @return int|void
     * @throws \Bluz\Application\Exception\OrderException
     */
    public function createOne($data)
    {
        /**
         * Создёт Заказ в orders
         * Сохраняет продукты в order_products
         * Уменьшает кредит пользователя на сумму заказа
         * Производит расчет total
         * Применяет скидку
         * Модифицирует пользователя в users
         * Сохраняет скидку пользователя на момент создания заказа
         *
         * TODO
         * - уведомление пользователю
         * - логирование в logs
         * - Модифицирует сессию identity
         */

        $this->exception = new \Bluz\Application\Exception\OrderException( );

        if( !isset($data['payment_types_key']) ) $data['payment_types_key'] = 'credit';

        $total = 0; // null
        $calculate_total = true;

        /*
        if(!is_null($this->total))
            $total = $this->total;

        if(is_null($total)){
            if(isset($data['total']))
                $total = $data['total'];
        }

        if(is_null($total)){
            $calculate_total = true;
            $total = 0;
        }
        */

        $this->validateUser($data);

        $user = $data['user'];
        $discount = (int)$user->discount;
        $usercredit_current = $user->getCredit();
        $data['users_id'] = $user->id;

        if( !isset($data['user_discount']) )
            $data['user_discount'] = $discount;

        /*
        $row = $this->getTable()->create();
        $row->setFromArray($data);
        $row->save();
        $orders_id = $row->orders_id;
        */

        // [!] родительский метод возвращает массив
        $order = parent::createOne($data);

        $orders_id = false;
        if(isset($order['orders_id'])){
            $orders_id = $order['orders_id'];
        } else {
            $this->exception->setCode(2);
            throw $this->exception;
        }

        if(sizeof($data['products'])){
            foreach($data['products'] as $products_id => $products_num){
                $product = \Application\Products\Table::findRow(array('products_id' => $products_id));

                $insertBuilder        = app()->getDb()
                    ->insert( 'order_products' )
                    ->set( 'orders_id', $orders_id )
                    ->set( 'products_id', $products_id )
                    ->set( 'price', $product->products_shoppingcart_price )
                    ->set( 'products_num', $products_num )
                ;
                $insertBuilder->execute();

                if($calculate_total){
                    $product_total = $products_num * $product->products_shoppingcart_price;
                    $total += $product_total;
                }
            }

            if($calculate_total){
                if($discount > 0 AND $discount < 100){
                    // Применить скидку
                    $discount_summ = $total * $discount / 100;
                    $total_discounted = $total - $discount_summ;
                    $total = $total_discounted;
                }

                // Update
                $updateBuilder = app()->getDb()
                    ->update('orders')
                    ->setArray(
                        array(
                            'total' => $total,
                        )
                    )
                    ->where('orders_id = ?', $orders_id);
                $updateBuilder->execute();
            }

            if($data['payment_types_key'] == 'credit'){
                if($total > ($usercredit_current * 1) ){
                    Crud::deleteOne(['orders_id' => $orders_id]);

                    $this->exception->setCode(3);
                    throw $this->exception;
                }
            }

        }

        $orders_to_bonus = $user->orders_to_bonus;
        $orders_to_bonus--;

        if($orders_to_bonus == 0) {

            $updateBuilder = app()->getDb()
                ->update('users')
                ->setArray(
                    array(
                        'presents' => ++$user->presents
                    )
                )
                ->where('id = ?', $user->id);
            $updateBuilder->execute();
            $orders_to_bonus = 10;
        }

        if($data['payment_types_key'] == 'credit')
            $usercredit =  $usercredit_current - $total;
        else
            $usercredit = $usercredit_current;

        $updateBuilder = app()->getDb()
            ->update('users')
            ->setArray(
                array(
                    'credit' => $usercredit,
                    'orders_to_bonus' => $orders_to_bonus,
                )
            )
            ->where('id = ?', $user->id);
        $updateBuilder->execute();

        app()->getRegistry()->new_orders_id = $orders_id;

        /**
         * перезаписать сессиюю
         */
        $_user = \Application\Users\Table::findRow(['id' => $user->id]);
        app()->getSession()->identity = $_user;
    }

    public function validate($primary, $data)
    {

    }

    public function validateCreate($data)
    {
        if(!sizeof($data['products']))
            if((int)$data['order_type'] === Table::ORDERTYPE_FRONTEND){
                $this->addError('Нет продуктов','products');
                $this->exception->setCode(1);
                throw $this->exception;
            }



        //TODO валидация полей
    }

    private function validateUser($data)
    {
        if(!isset($data['user'])){
            $this->exception->setCode(4);
            throw $this->exception;
        }

        if($data['user'] instanceof  Users\Row) {}
        else {
            $this->exception->setCode(4);
            throw $this->exception;
        }
    }


    public function validateUpdate($primary, $data)
    {
        if(false){
            $this->addError('error','fieldname');
        }
    }

    /**
     * @param mixed $primary_key
     * @return Row
     */
    function readOne($primary_key)
    {
    /**
     * Взять
     * - все записи в логах
     * - все продукты
     * - оплачен или нет
     * - имя юзера
     * - тип цен
     * - тип оплаты
     * - телефон пользователя
     */
        if(!$primary_key){
            // Если, вдруг, будет попытка выгрести ордер через этот метод
            return parent::readOne($primary_key);

        } elseif(is_array($primary_key)){
            if(isset($primary_key['orders_id'])){
                // Выгребаем ордер

                $order = parent::readOne($primary_key);

                $user = app()->getDb()->fetchRow ("
                    SELECT u.*
                     FROM users u
                    WHERE  id = '".$order->users_id."' ");
                $order->user = $user;

                $payment_type = app()->getDb()->fetchRow ("
                    SELECT pt.*
                     FROM payment_types pt
                    WHERE  payment_types_id = '".$order->payment_types_id."' ");
                $order->payment_type = $payment_type;

                $order->order_status_str = Table::getInstance()->order_status_arr[$order->order_status];
                $order->order_type_str = Table::getInstance()->order_type_arr[$order->order_type];

                $products = app()->getDb()->fetchAll ("
                    SELECT op.*, p.*
                     FROM order_products op
                    JOIN products p ON p.products_id = op.products_id
                    WHERE  orders_id = '{$primary_key['orders_id']}' ");

                if(sizeof($products)){
                    $products_total = 0;
                    foreach($products as $product){
                        $products_total += $product['price'] * $product['products_num'];
                    }
                    $order->brutotal = $products_total;
                }

                $order->products = $products;

                //fb($products[0]['products_id']);

                return $order;
            }
        }

    }

    function updateOne($primary, $data)
    {
        $total  = 0;

        $selectBuilder = app()->getDb()
            ->select('op.*')
            ->from('order_products', 'op')
            ->where("orders_id = '{$primary['orders_id']}'");
        $products = $selectBuilder->execute();

        if(sizeof($products)){
            $products_total = 0;
            foreach($products as $product){
                $products_total += $product['price'] * $product['products_num'];
            }
            $discount = $data['user_discount'] / 100;
            $total = $products_total - ($products_total * $discount);
        }

        $data['total'] = $total;

        return parent::updateOne($primary, $data);
    }






}
