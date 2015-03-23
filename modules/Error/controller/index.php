<?php
/**
 * Error controller
 * Send error headers and show simple page
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:32
 */
namespace Application;

use Bluz;
use Bluz\Request;

return
/**
 * @route  /error/{$code}
 * @param  int $code
 * @param  string $message
 *
 * @return \closure
 */
function ($code, $message = '') use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    Bluz\Application\Application::getLoggerStatic()->error($message);
    //$this->getLogger()->error($message);

    switch ($code) {
        case 400:
            $title = __("Bad Request");
            $description = __("The server didn't understand the syntax of the request");
            break;
        case 401:
            $title = __("Unauthorized");
            $description = __("You are not authorized to view this page, please sign in");
            break;
        case 403:
            $title = __("Forbidden");
            $description = __("You don't have permissions to access this page");
            break;
        case 404:
            $title = __("Not Found");
            $description = __("The page you requested was not found");
            break;
        case 405:
            $title = __("Method Not Allowed");
            $description = __("The server is not support method");
            break;
        case 500:
            $title = __("Internal Server Error");
            $description = __("The server encountered an unexpected condition");
            break;
        case 501:
            $title = __("Not Implemented");
            $description = __("The server does not understand or does not support the HTTP method");
            break;
        case 503:
            $title = __("Service Unavailable");
            $description = __("The server is currently unable to handle the request due to a temporary overloading");
            break;
        default:
            $code = 500;
            $title = __("Internal Server Error");
            $description = __("An unexpected error occurred with your request. Please try again later");
            break;
    }

    // send headers, if possible
    if ('cli' == PHP_SAPI or !headers_sent()) {
        // it's works for CLI too
        http_response_code($code);
        switch ($code) {
            case 405:
                header('Allow: '. $message);
                break;
            case 503:
                header('Retry-After: 600');
                break;
        }
    }

    $boot_object = Bootstrap::getInstance(); // Application\Bootstrap

    // check CLI or HTTP request
    if (!$boot_object->getRequest()->isCli()) {
        $accept = $boot_object->getRequest()->getHeader('accept');
        $accept = substr($accept, 0, strpos($accept, ','));

        // simple AJAX call
        if ($boot_object->getRequest()->isXmlHttpRequest()
            && $accept == "application/json") {

            $boot_object->getMessages()->addError($message);
            //return $view;
            return $view->render();
        }

        // dialog AJAX call
        if (!$boot_object->getRequest()->isXmlHttpRequest()) {
            $boot_object->useLayout('small.phtml');
        }
    }

    $view->title = $title;
    $view->description = $description;
    $view->message = $message;
    $boot_object->getLayout()->title($title);
};
