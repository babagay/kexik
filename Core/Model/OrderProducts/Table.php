<?php

namespace Application\OrderProducts;

class Table extends \Bluz\Db\Table
{


    /**
     * Table
     *
     * @var string
     */
    protected $table = 'order_products';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('products_id','orders_id');




}
