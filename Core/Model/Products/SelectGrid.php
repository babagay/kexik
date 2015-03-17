<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Products;


/**
 * Products Grid based on SQL
 *
 * @category Application
 * @package  Products
 */
class SelectGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'sql';

    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
         // Array
         $adapter = new \Bluz\Grid\Source\SelectSource();

         $select = new \Bluz\Db\Query\Select();
         $select->select('*')->from('products', 't');

         $adapter->setSource($select);

         $this->setAdapter($adapter);
         $this->setDefaultLimit(15);
         $this->setAllowOrders(['name', 'id', 'status']);
         $this->setAllowFilters(['status', 'id']);

         return $this;
    }
}
