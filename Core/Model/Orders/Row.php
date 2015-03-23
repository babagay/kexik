<?php

namespace Application\Users;

use Application\Exception; 
use Bluz\Auth\AbstractRowEntity; 

 
class Row extends AbstractRowEntity
{
     
     

    function getData()
    {
        return $this->data;
    }
}
