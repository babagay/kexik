<?php
return

    function () use ($view) {

        //$app_object = Application\Bootstrap::getInstance();
        $app_object = app()->getInstance();

        $title = 'Admin';


        //$app_object->getLayout()->title($title);
        $app_object->getLayout()->title('Пользователи', 'rewrite');


        $app_object->getLayout()->breadCrumbs(
            array(
                $view->ahref('Админка', array('admin', 'Base')),
                'Управление пользователями',
            )
        );

        // change layout
        $app_object->useLayout('backend.phtml');

        /// $app_object->getLayout()->breadCrumbs($crumbs_arr);
    };