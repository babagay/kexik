<?php

namespace Core\Helper;

/*
  Вызов: $a = new \Core\Helper\Asd();
*/

class Asd {

  function __construct()
  {
    fb(__CLASS__);
  }

  function __invoke(){
            return [1=>2];
    }

}