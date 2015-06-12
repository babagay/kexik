<?php
/**
 * Login module/controller
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 */
namespace Application;

use Bluz;
use Bluz\Auth\AuthException;
use Application\Auth;

$_this = $this;

return
    /**
     * @param string $login
     * @param string $password
     * @return \closure
     */
    function ($login, $password = null) use ($view, $_this) {
        /**
         * @var \Application\Bootstrap $_this
         * @var \Bluz\View\View $view
         */

        $l = false;

        if (is_array($login)) {
            $l = $login[0];
            if (isset($login[1]))
                $password = $login[1];
        }

        if ($l !== false) $login = $l;


        if ($_this->getAuth()->getIdentity()) {
            $_this->getMessages()->addNotice('Already signed');
            $_this->redirectTo('index', 'index');
        } elseif ($_this->getRequest()->isPost()) {
            try {
                if (empty($login)) {
                    throw new Exception("Login is empty");
                }

                if (empty($password)) {
                    throw new Exception("Password is empty");
                }

                // login/password
                Auth\Table::getInstance()->authenticateEquals($login, $password);

                $_this->getMessages()->addNotice('You are signed');

                // try to rollback to previous called URL
                if ($rollback = $_this->getSession()->rollback) {
                    unset($_this->getSession()->rollback);
                    $_this->redirect($rollback);
                }
                // try back to index
                $_this->redirectTo('index', 'index');
            } catch (Exception $e) {
                $_this->getMessages()->addError($e->getMessage());
                $view->login = $login;
            } catch (AuthException $e) {
                $_this->getMessages()->addError($e->getMessage());
                $view->login = $login;
            }
        }
        // change layout
        $_this->useLayout('small.phtml');
    };
