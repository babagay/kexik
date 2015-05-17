<?php


//namespace Core\Model\Orders;
namespace Application\Orders;

use Application\Auth;
use Application\Exception;

use Application\UsersActions;
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


        $total = null;
        $calculate_total = false;

        if(!is_null($this->total)) $total = $this->total;
        if(is_null($total)){
            if(isset($data['total']))
                $total = $data['total'];
        }

        ///$user = app()->getAuth()->getIdentity();
        $user = $this->user;
        $discount = (int)$user->discount;

        $data['users_id'] = $user->id;

        if( !isset($data['user_discount']) )
            $data['user_discount'] = $discount;

        $row = $this->getTable()->create();
        $row->setFromArray($data);
        $row->save();
        $orders_id = $row->orders_id;


        if(!$orders_id){
            // TODO проверить
            $this->exception->setCode(2);
            throw $this->exception;
        }


        if(is_null($total)){
            $calculate_total = true;
            $total = 0;
        }

        if(sizeof($this->products)){
            foreach($this->products as $products_id => $products_num){
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
        } else {
            if((int)$data['order_type'] === Table::ORDERTYPE_FRONTEND){
                $this->exception->setCode(1);
                throw $this->exception;
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

        app()->getSession()->identity->orders_to_bonus = $orders_to_bonus;

        /**
         * Можно перезаписать сессиюю:
         *  $_user = \Application\Users\Table::findRow( array('id' => 20) );
         * app()->getSession()->identity = $_user;
         */

        $updateBuilder = app()->getDb()
            ->update('users')
            ->setArray(
                array(
                    'credit' => $user->getCredit() - $total,
                    'orders_to_bonus' => $orders_to_bonus,
                )
            )
            ->where('id = ?', $user->id);
        $updateBuilder->execute();

        app()->getRegistry()->new_orders_id = $orders_id;
    }

    public function validateCreate($data)
    {
        if(false){
            $this->addError('error','fieldname');
        }
    }

    public function validateUpdate($id,$data)
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






}
