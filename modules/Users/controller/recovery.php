<?php
/**
 * Initialization password recovery
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  11.12.12 13:03
 */
namespace Application;

use Application\Users;
$_this = $this;
return
/**
 * @param string $email
 * @return \closure
 */
function ($email = null) use ($view, $_this) {
    /**
     * @var \Application\Bootstrap $_this
     * @var \Bluz\View\View $view
     */

    $eml = false;

    if(is_array($email)){
        $eml = $email[0];
    }

    if($eml !== false) $email = $eml;

    // change layout
    $_this->useLayout('small.phtml');

    if ($_this->getRequest()->isPost()) {
        try {
            // check email
            if (empty($email)) {
                throw new Exception('Email can\'t be empty');
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                list($user, $domain) = explode("@", $email, 2);
                if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
                    throw new Exception('Email has invalid domain name');
                }
            } else {
                throw new Exception('Email is invalid');
            }
            // check exists
            $user = Users\Table::findRowWhere(array('email' => $email));
            if (!$user) {
                throw new Exception('Email not found');
            }
            // check status, only for active users
            if ($user->status != Users\Table::STATUS_ACTIVE) {
                throw new Exception('User is inactive');
            }

            // create activation token
            // valid for 5 days
            $actionRow = UsersActions\Table::getInstance()->generate($user->id, UsersActions\Table::ACTION_RECOVERY, 5);

            // send activation email
            // generate restore URL
            $resetUrl = $_this->getRouter()->getFullUrl(
                'users',
                'recovery-reset',
                array('code' => $actionRow->code, 'id' => $user->id)
            );

            $subject = "Password Recovery";

            $body = $_this->dispatch(
                'users',
                'mail-template',
                array(
                    'template' => 'recovery',
                    'vars' => array('user' => $user, 'resetUrl' => $resetUrl)
                )
            )->render();

            try {
                $mail = $_this->getMailer()->create();

                // subject
                $mail->Subject = $subject;
                $mail->MsgHTML(nl2br($body));

                $mail->AddAddress($user->email);

                $_this->getMailer()->send($mail);

            } catch (\Exception $e) {
                // log it
                app()->getLogger()->log(
                    'error',
                    $e->getMessage(),
                    array('module' => 'users', 'controller' => 'recovery', 'email' => $email)
                );
                throw new Exception('Unable to send email. Please contact administrator.');
            }

            // show notification and redirect
            $_this->getMessages()->addSuccess(
                "Reset password instructions has been sent to your email address"
            );
            $_this->redirectTo('index', 'index');

        } catch (Exception $e) {
            $_this->getMessages()->addError($e->getMessage());
        }
        $view->email = $email;
    }
};
