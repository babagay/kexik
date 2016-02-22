<?php

return
    /**
     * @return \closure
     * privilege Edit
     */
    function () use ($view) {

        $title = 'Личный кабинет';

        $user = app()->getAuth()->getIdentity();

        if (!is_object($user))
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        app()->getLayout()->title('Заказы');

        $view->users_id = $user->id;

        app()->getLayout()->breadCrumbs(
            array(
                $view->ahref($title, array('кабинет', '')),
                'Просмотр заказов',
            )
        );

    };