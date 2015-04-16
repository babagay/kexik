<?php


//namespace Core\Model\Products;
namespace Application\Users;


/**
 * Products Grid based on SQL
 *
 * @category Application
 * @package  Products
 */
class SqlGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'users';
    protected $table_name = 'users';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        $adapter = new \Bluz\Grid\Source\SqlSource();

        $key = null;

        $adapter->setSource('SELECT * FROM ' . $this->table_name);


        $this->setAdapter($adapter);
        $this->setDefaultLimit(500);
        $this->setAllowOrders(array('id', 'login', 'email', 'created', 'updated', 'status', 'credit', 'discount', 'orders_to_bonus', 'presents'));
        $this->setAllowFilters(array('id', 'login', 'email', 'status'));


        return $this;
    }


}
