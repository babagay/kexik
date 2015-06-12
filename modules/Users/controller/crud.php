<?php
/**
 * @author   Anton Shevchuk
 * @created  30.10.12 09:29
 */
namespace Application;

use Application\Users;
use Bluz\Controller;

return
    /**
     * @privilege Management
     * @return \closure
     */
    function () {
        /**
         * @var \Application\Bootstrap $this
         */
        $crudController = new Controller\Crud();
        $crudController->setCrud(Users\Crud::getInstance());

        app()->useLayout(false);

        $status = 'ok';
        $result = 'saved';

        if (app()->getRequest()->getMethod() == 'PUT') {
            // Сохранение параметров пользователя
            $crud = $crudController();

            if (isset($crud['errors'])) {
                $status = 'error';
                $result = 'not saved';

                //TODO выводить ошибки
                //fb($crud['errors']);

            } else {
                // Обновление сессии
                $id                           = app()->getRequest()->getParam('id', null);
                $_user                        = \Application\Users\Table::findRow(['id' => $id]);
                app()->getSession()->identity = $_user;

            }

            return function () use ($status, $result) {
                return ['status' => $status, 'result' => $result];
            };
        }

        if (!app()->getRequest()->isPost()) {

            return $crudController();

        } else {
            // Вызов аяксом

            $crud = $crudController();

            if ($crud === null)
                // При создании новой записи
                return $crud;

            // При возникновении ошибок
            return function () use ($crud) {
                return $crud;
            };
        }

    };
