<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 */
namespace Application;

use Application\Users;
use Application\UsersActions;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Auth\AuthException;
use Bluz\Controller;
use Bluz\Request\AbstractRequest;

return
/**
 * @privilege EditEmail
 * @return \closure
 */
function ($password, $new_password, $new_password2) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */

    $app_object = app()->getInstance();

    // change layout
    $app_object->useLayout('small.phtml');

    $userId = $app_object->getAuth()->getIdentity()->id;

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($userId);

    if (!$user) {
        throw new NotFoundException('User not found');
    }

    $view->uid = uniqid('form_');

    if ($app_object->getRequest()->isPost()) {
        // process form
        try {
            if (empty($password)) {
                throw new Exception('Please input current password');
            }

            if (empty($new_password)) {
                throw new Exception('Please input new password');
            }

            if (empty($new_password2)) {
                throw new Exception('Please repeat new password');
            }

            $authTable = Auth\Table::getInstance();

            // password check
            $authTable->checkEquals($user->login, $password);
            // create new Auth record
            $authTable->generateEquals($user, $new_password);

            $app_object->getMessages()->addSuccess("The password was updated successfully");

            // try back to index
            $app_object->redirectTo('users', 'profile');
        } catch (Exception $e) {
            $app_object->getMessages()->addError($e->getMessage());
        } catch (AuthException $e) {
            $app_object->getMessages()->addError($e->getMessage());
        }
    }
};
