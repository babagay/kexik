<?php
/**
 * Edit profile controller.
 *
 * @author  Sergey Volkov
 * @created 29.05.2013 17:20
 *
 *          Описание майлера в Блюз-вики:
 *          https://github.com/bluzphp/framework/wiki/Mailer
 *          https://github.com/PHPMailer/PHPMailer - PHPMailer
 */
namespace Application;

use Application\Users;
use Application\UsersActions;
use Application\UsersActions\Table;
use Bluz\Application\Exception\NotFoundException;
use Bluz\Auth\AuthException;
use Bluz\Controller;

$_this = $this;
return
/**
 * @privilege EditEmail
 * @return \closure
 */
function ($email = null, $password = null, $token = null) use ($view, $_this) {
    /**
     * @var \Application\Bootstrap $this
     */

    $e = false;

    /**
     * Имя пользователя: babagayr
     * Пароль: 	Использовать пароль учетной записи электронной почты автора.
     * Входящий сервер (Non-SSL): mail.babagay.ru
     *    IMAP Порт: 143
     *    POP3 порт: 110
     * Входящий сервер (SSL / TLS):    s205.avahost.net
     *    IMAP Порт: 993
     *    POP3 порт: 995
     * Сервер исходящей почты  (Non-SSL): mail.babagay.ru
     *    Порт SMTP: 26
     * Сервер исходящей почты (SSL / TLS):    s205.avahost.net
     *    Порт SMTP: 465
     * Проверка подлинности требуется для IMAP, POP3, SMTP
     */


    $mail = new \PHPMailer();

    $mail->isSMTP();                                      // Set mailer to use SMTP

    // 91.197.129.190

    //$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
    $mail->Host = 's205.avahost.net';
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //$mail->Username = 'user@example.com';                 // SMTP username
    $mail->Username = 'babagayr@babagay.ru';
    //$mail->Password = 'secret';                           // SMTP password
    $mail->Password = 'LJet118w3g';
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 587;
    $mail->Port = 465;

    $mail->From = 'from@example.com';
    $mail->FromName = 'Mailer';
    //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('wonderer@i.ua', 'User');
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        fb( 'Mailer Error: ' . $mail->ErrorInfo );
    } else {
        fb( 'Message has been sent' );
    }


    return;

    if(is_array($email)){
        if(isset($email[0])){
            $e = $email[0];
        }
        if(isset($email[1])){
            $password = $email[1];
        }
        if(isset($email[2])){
            $token = $email[2];
        }
    }

    //fb($email);
    //fb($password);

    if($e !== false) $email = $e;

    $view->form_id = uniqid("uniqid");

    // change layout
    $_this->useLayout('small.phtml');

    $userId = $_this->getAuth()->getIdentity()->id;

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($userId);

    if (!$user) {
        throw new NotFoundException('User not found');
    }

    $view->email = $user->email;

    if ($_this->getRequest()->isPost()) {
        // process form
        try {
            if (empty($password)) {
                throw new Exception('Password is empty');
            }

            // login/password
            Auth\Table::getInstance()->checkEquals($user->login, $password);

            // check email for unique
            $emailUnique = Users\Table::getInstance()->findRowWhere(array('email' => $email));
            if ($emailUnique && $emailUnique->id != $userId) {
                throw new Exception('User with email "'.htmlentities($email).'" already exists');
            }

            // generate change mail token and get full url
            $actionRow = UsersActions\Table::getInstance()->generate(
                $userId,
                Table::ACTION_CHANGE_EMAIL,
                5,
                array('email' => $email)
            );

            $changeUrl = $_this->getRouter()->getFullUrl(
                'users',
                'change-email',
                array('token' => $actionRow->code)
            );

            $subject = __("Change email");

            // Можно срендерить темплейт в переменную
            $body = $_this->dispatch(
                'users',
                'mail-template',
                array(
                    'template' => 'change-email',
                    'vars' => array(
                        'user' => $user,
                        'email' => $email,
                        'changeUrl' => $changeUrl,
                        'profileUrl' => $_this->getRouter()->getFullUrl('users', 'profile')
                    )
                )
            )->render();


            // Это корректный вариант возврата для Accept = json
            // return function() use ($body) {                return array('body' => $body); };

            // FIXME рендерится правильное $body, но не отсылается письмо на мыло
            // TODO что должен возвращать return?

            try {
                $mail = $_this->getMailer()->create();

                // subject
                $mail->Subject = $subject;
                $mail->MsgHTML(nl2br($body));

                $mail->AddAddress($email);

                $_this->getMailer()->send($mail);

                $_this->getMessages()->addNotice('Check your email and follow instructions in letter.');

            } catch (\Exception $e) {
                $_this->getLogger()->log(
                    'error',
                    $e->getMessage(),
                    array('module' => 'users', 'controller' => 'change-email', 'userId' => $userId)
                );
                throw new Exception('Unable to send email. Please contact administrator.');
            }

            // try back to index
            $_this->redirectTo('users', 'profile');
        } catch (Exception $e) {
            $_this->getMessages()->addError($e->getMessage());
            $view->email = $email;
        } catch (AuthException $e) {
            $_this->getMessages()->addError($e->getMessage());
            $view->email = $email;
        }
    } elseif ($token) {
        // process activation
        $actionRow =   UsersActions\Table::findRowWhere(array('code' => $token, 'userId' => $userId));

        if (!$actionRow) {
            throw new Exception('Invalid token');
        }

        $params = $actionRow->getParams();

        $user->email = $params['email'];
        $user->save();

        $actionRow->delete();

        $_this->getMessages()->addSuccess('Email was updated');
        $_this->redirectTo('users', 'profile');
    }
};
