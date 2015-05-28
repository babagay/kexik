<?php
/*
 * URL для вызова: http://127.0.0.1/zoqa/my/base/question/2+2
 *
 * TODO Рассмотреть
 * - как сделать возможность вызова метода по-зендовски (через имя точки входа)
 * - попробовать взять параметры из урла прямо здесь (в замыкании)
 *
 * - прикрутить шаблонизатор
 *
 * [пример аякс]
 * var basePath = 'http://127.0.0.1/zoqa/'
$.post(basePath+"my/Base/вопрос/78", {asd: 'asd'}, function (res) {
    }, "json");
 */
return
    /**
     * @return \closure
     * ____pri vileg e Edit
     */
    function () use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
        $app_object = app()->getInstance();

        $uri_param_2 = app()->getRequest()->get(2);

        // change layout
        $app_object->useLayout('front_end.phtml');

        switch ($uri_param_2) {
            case 'мой_профиль':
                return app()->dispatch('users', 'profile');
                break;
            case 'мои_заказы':
                return app()->dispatch('my', 'orders');
                break;
            default:
                break;
        }


        $title = 'Личный кабинет';

        $app_object->getLayout()->title($title);


        $crumbs_arr = array(
            //$view->ahref('Автор', array('автор', '') ),
            __($title)
        );


        $app_object->getLayout()->breadCrumbs($crumbs_arr);
    };