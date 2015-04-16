<?php
    return

        function () use ($view) {

            //$app_object = Application\Bootstrap::getInstance();
            $app_object = app()->getInstance();

            $title = 'Admin';

            $crumbs_arr =  array(
                __($title)
            );

            $user = app()->getAuth()->getIdentity();

            if(!is_object($user))
                throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет",404);

            $access_is_open = $user->hasPrivilege($module = 'admin', $privilege = 'Management');
            if($access_is_open !== true)
                throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет",404);



            //$app_object->getLayout()->title($title);
            $app_object->getLayout()->title('Продукты', 'rewrite');



            $app_object->getLayout()->breadCrumbs(
                array(
                    $view->ahref('Админка', array('admin', 'Base')),
                    'Управление продуктами',
                )
            );

            // change layout
            $app_object->useLayout('backend.phtml');

           /// $app_object->getLayout()->breadCrumbs($crumbs_arr);

        };