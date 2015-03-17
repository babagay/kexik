<?php
/**
 * Example of Crud Controller
 *
 * @author   Anton Shevchuk
 * @created  04.09.12 11:21
 */
namespace Application;

use Application\Test;
use Bluz\Controller;
use Core\Model\ArticleGroups\Table;

return function () {
    $crudController = new Controller\Crud();

    $crudController->setCrud(Test\Crud::getInstance());

    /*
    // TODO Как создать экземпляр таблицы
    $table =   \Core\Model\Articles\Table();

    чтобы передать его в  setTable()?

    $r = new \Bluz\Crud\Table();
    $r->setTable();
    */

    return $crudController();
};
