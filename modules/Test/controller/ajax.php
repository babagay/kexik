<?php
/**
 * Test AJAX
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure
 */
namespace Application;

use Bluz;

$_this = $this;

return
/**
 * @param sd
 * @param bool $messages
 * @return \closure
 */
function ($messages = false, $asd = null) use ($view,$_this) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    //fb($messages); true
    //fb($asd); 1
    //fb($_this); // Application\Bootstrap

    if ($messages) {
        $_this->getMessages()->addNotice('Notice Text');
        $_this->getMessages()->addSuccess('Success Text');
        $_this->getMessages()->addError('Error Text');
    }
    $_this->getMessages()->addNotice('Method '. $_this->getRequest()->getMethod());

    $view->foo = 'bar';

    $view->time = date( "Y-m-d H:i:s", time());
    //$_this->reload();
    //$_this->redirect('http://google.com');
    //$_this->redirectTo('test', 'index');

    sleep(2);


};
