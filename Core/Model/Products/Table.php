<?php
 

/**
 * @namespace
 */
namespace Application\Products;

/**
 * Table
 *
 * @category Application
 * @package  Products
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('products_id');

    /**
     * save test row - Example
     *
     * @return boolean
     */
    public function saveTestRow()
    {
        return $this->insert(array('name'=>'Example #'.rand(1, 10), 'email'=> 'example@example.com'));
    }

    /**
     * update test row - Example
     *
     * @return boolean
     */
    public function updateTestRows()
    {
        return $this->update(array('email'=> 'example2@example.com'), array('email'=> 'example@example.com'));
    }

    /**
     * delete test row  - Example
     *
     * @return boolean
     */
    public function deleteTestRows()
    {
        return $this->delete(array('email'=> 'example2@example.com'));
    }
}
