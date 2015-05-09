<?php

namespace Application\Orders;
 
class Table extends \Bluz\Db\Table
{
    /**
     * Статусы заказа
     */
    const STATUS_OPEN = 1; // новый заказ
    const STATUS_CLOSED = 2; // успешно отработан
    const STATUS_CANCELED = 0; // отменен

    // TODO типы ордеров - бекенд фронтенд

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('orders_id');



     
}
