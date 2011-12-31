<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 29.04.14
 * Time: 11:56
 */

    // Не срабатывает без прописывания мап-файл
    // Для работы необходимо добавить запись в autoload_psr4.php
    // Вызов: $e = new \Zoqa\Testspace\zxc();
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