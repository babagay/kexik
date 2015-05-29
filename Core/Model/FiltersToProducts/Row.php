<?php

/**
 * @namespace
 */
namespace Application\FiltersToProducts;

/**
 * FiltersToProducts Row
 *
 * @category Application
 * @package  FiltersToProducts
 *
 * @property integer $products_id
 * @property integer $filters_id
 *
 */
class Row extends \Bluz\Db\Row
{
    /**
     * __insert
     *
     * @return void
     */
    public function beforeInsert()
    {
        ///$this->created = gmdate('Y-m-d H:i:s');
    }

    /**
     * __update
     *
     * @return void
     */
    public function beforeUpdate()
    {
        ///$this->updated = gmdate('Y-m-d H:i:s');
    }
}
