<?php
/**
 * Mailer for Users
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  06.12.12 12:43
 */
namespace Application;

return
/**
 * @param string $template
 * @param array $vars
 * @return \closure
 */
function ($template, $vars = array()) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    $t = false;

    if(is_array($template)){
        $t = $template[0];
        if(isset($template[1]))
            $vars = $template[1];
    }

    if($t !== false) $template = $t;

    $view->setTemplate('mail/' . $template . '.phtml');
    return $vars;
};
