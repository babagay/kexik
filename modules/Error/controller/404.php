<?php

return

    function () use ($view) {

         app()->dispatch(
            'error',
            'Base',
            array(
                'code' => '404',
                'message' => 'Страница не найдена',
            )
        )  ;

        $view->title = '404 ошибка';
        $view->description = 'Error 404';
        $view->message = 'Страница не найдена';

        return 'index.phtml';

    };
