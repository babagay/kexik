<?php

/**
 * @namespace
 */
namespace Application\Filters;

/**
 * Filters Row
 *
 * @category Application
 * @package  Filters
 *
 * @property integer $filters_id
 * @property string $name
 * @property string $key
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
