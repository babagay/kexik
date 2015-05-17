<?php
/**
 * Table
 *
 * @category Application
 * @package  PaymentTypes
 */
namespace Application\PaymentTypes;

use Bluz\Cache\Cache;
use Bluz\Db\Db;

class Table extends \Bluz\Db\Table
{

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'payment_types';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('payment_types_id');


    public function getPaymentTypes()
    {
        return $this->fetch("SELECT * FROM ".   $this->table   ." ORDER BY payment_types_id");
    }




}
