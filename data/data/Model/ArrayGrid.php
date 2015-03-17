<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 28.04.14
 * Time: 18:46
 */

namespace Core\Model;

//namespace Application\Test;


/**
 * Test Grid based on Array
 *
 * @category Application
 * @package  Test
 */
class ArrayGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'arr';
    /**
     * init
     *
     * @return self
     */
    public function init()
    {
        // Array
        $adapter = new \Bluz\Grid\Source\ArraySource();
        $adapter->setSource(
            array(
                array('id'=>1, 'name'=>'Foo', 'email'=>'a@bc.com', 'status'=>'active'),
                array('id'=>2, 'name'=>'Bar', 'email'=>'d@ef.com', 'status'=>'active'),
                array('id'=>3, 'name'=>'Foo 2', 'email'=>'m@ef.com', 'status'=>'disable'),
                array('id'=>4, 'name'=>'Foo 3', 'email'=>'j@ef.com', 'status'=>'disable'),
                array('id'=>5, 'name'=>'Foo 4', 'email'=>'g@ef.com', 'status'=>'disable'),
                array('id'=>6, 'name'=>'Foo 5', 'email'=>'r@ef.com', 'status'=>'disable'),
                array('id'=>7, 'name'=>'Foo 6', 'email'=>'m@ef.com', 'status'=>'disable'),
                array('id'=>8, 'name'=>'Foo 7', 'email'=>'n@ef.com', 'status'=>'disable'),
                array('id'=>9, 'name'=>'Foo 8', 'email'=>'w@ef.com', 'status'=>'disable'),
                array('id'=>10, 'name'=>'Foo 9', 'email'=>'l@ef.com', 'status'=>'disable'),
            )
        );

        $this->setAdapter($adapter);
        $this->setDefaultLimit(3);
        $this->setAllowOrders(array('name', 'email', 'id'));
        $this->setAllowFilters(array('status', 'id'));

        return $this;
    }
}