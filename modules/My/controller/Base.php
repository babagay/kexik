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
     * @param string $question
     * @return \closure
     */
    function ($question = null, $asd = null, $вопрос = null, $code = null) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
        $app_object = app()->getInstance();

        $title = 'Автор';
		



        $app_object->getLayout()->title($title);



        $crumbs_arr =  array(
            //$view->ahref('Автор', array('автор', '') ),
            __($title)
        );

        // change layout
        $app_object->useLayout('front_end.phtml');

        $app_object->getLayout()->breadCrumbs($crumbs_arr);
    };