<?php
/**
 * Logout proccess
 *
 * @author   Anton Shevchuk
 * @created  20.07.11 18:39
 * @return closure
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $_this = app();

    $_this->getAuth()->clearIdentity();
    $_this->getMessages()->addNotice('You are signout');
    $_this->redirectTo('index', 'index');
};
