<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 29.04.14
 * Time: 11:56
 */

namespace Zoqa\Testspace;


class zxc {

    function __construct()
    {
        fb(__CLASS__);
    }

    function __call($name,$params){

        fb($name);
        fb($params);

    }

} 