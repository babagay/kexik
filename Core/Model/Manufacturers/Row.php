<?php

namespace Application\Manufacturers;

use Application\Exception; 
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;


class Row extends \Bluz\Db\Row
{
    /**
     * __insert
     *
     * @return void
     */
    public function beforeInsert()
    {
        //$this->date_added = gmdate('Y-m-d H:i:s');
    }

    function getData()
    {
        return $this->data;
    }


}
