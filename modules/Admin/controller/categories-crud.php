<?php

namespace Application;

use Application\Categories;
use Bluz\Controller;
//use Core\Model\Categories;
//use Core\Model\ArticleGroups\Table;

return function () {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Categories\Crud::getInstance());

    /*
    // TODO Как создать экземпляр таблицы
    $table =   \Core\Model\Articles\Table();

    чтобы передать его в  setTable()?

    $r = new \Bluz\Crud\Table();
    $r->setTable();
    */



    return $crudController();
};
