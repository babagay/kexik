<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
//namespace Core\Model\Products;
namespace Application\Orders;


/**
 * Products Grid based on SQL
 *
 * @category Application
 * @package  Products
 */
class SqlGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'orders';
    protected $table_name = 'orders';

    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        $adapter = new \Bluz\Grid\Source\SqlSource();

        $key = null;

        if (isset($this->options['users_id'])) {
            $adapter->setSource('SELECT o.*, u.login, u.email
             FROM ' . $this->table_name . ' o
             LEFT JOIN users u ON u.id = o.users_id
             WHERE u.id = ' . " '" . $this->options['users_id'] . "'"
            );
        } else {
            $adapter->setSource('SELECT o.*, u.login, u.email
             FROM ' . $this->table_name . ' o
             LEFT JOIN users u ON u.id = o.users_id '
            );
        }

        $this->setAdapter($adapter);
        $this->setDefaultLimit(50);

        $this->setAllowOrders(array('orders_id', 'users_id', 'date_added', 'address', 'total', 'notes'));
        $this->setDefaultOrder('date_added', "DESC");
        $this->setAllowFilters(array('orders_id', 'users_id', 'date_added', 'address', 'login'));

        return $this;
    }

}
