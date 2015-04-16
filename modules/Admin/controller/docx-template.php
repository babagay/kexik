<?php

namespace Application;

return

    function ($template, $vars = array()) use ($view) {
        /**
         * @var \Application\Bootstrap $this
         * @var \Bluz\View\View $view
         */

        $view->products = $vars['products'];
        $view->total = $vars['total'];


        $view->setTemplate('docx/'.$template.'.phtml');

        //return $vars;
    };
