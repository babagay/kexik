<?php
/**
 * Example of Mailer usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

return
/**
 * @param string $email
 * @return \closure
 */
function ($email = "no-reply@nixsolutions.com") use ($view) {
    /**
     * @var \Application\Bootstrap $this
     */
    $_this = app()->getInstance();

    $_this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Mailer Example',
            )
    );
    if ($_this->getRequest()->isPost()) {
        try {
            $mail = $_this->getMailer()->create();
            // subject
            $mail->Subject = "Example of Bluz Mailer";
            $mail->MsgHTML("Hello!<br/>How are you?");
            $mail->AddAddress($email);
            $_this->getMailer()->send($mail);
            $_this->getMessages()->addSuccess("Email was send");
        } catch (\Exception $e) {
            $_this->getMessages()->addError($e->getMessage());
        }
    }
    $view->email = $email;

    $_this->useLayout('front_end.phtml');
};
