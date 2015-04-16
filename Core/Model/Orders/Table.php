<?php

namespace Application\Orders;
 
class Table extends \Bluz\Db\Table
{
     

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
