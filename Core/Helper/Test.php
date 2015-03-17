<?php

//namespace Bluz\Application\Helper;

/**
 * Class Test
 * namespace в прямой нотации (соответсвует физическому пути). Должен быть задан явно в любом случае.
 * Имя класса должно совпадать с именем файла
 * Для произвольного неймспеса - добавить запись в autoload_psr4.php
 * Вызов: $e = new Core\Helper\Test();, если здесь класс
 * или $app_object->Test();, если здесь замыкание
 *
 */

    /*
class Test { // работает

    function __construct()
    {
        fb(__CLASS__);
    }

    function __call($name,$params){

        fb($name);
        fb($params);

    }

}
        */
namespace Core\Helper;



return
    /**
     * При передаче параметров в замыкание они все сваливаются в элементы массива $param
     */
    function ($param) {

        fb($param);

    };


