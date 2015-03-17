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
// $_this = $this;
 
return
    /**
     * @param string $question
     * @return \closure
     */
    function ($question = null, $asd = null, $вопрос) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view
         */
        //$app_object = Application\Bootstrap::getInstance();
		// Альтернатива $_this = $this;
        $app_object = app()->getInstance();
		
		 

        ///fb($app_object->getRequest()->getParams());
       
        

        // FIXME Если отсюда поппытаться переререть zoqa_title, эффект нулевой
        // $view->zoqa_title = '$answer';
        $app_object->getLayout()->title('');

        //return array('question' => $question, 'answer' => $answer);
		
		
		// change layout
		$app_object->useLayout('front_end.phtml');
	
		// Такая конструкция загрузит шаблон
		// return 'Test.phtml';
		// Test.phtml грузится и так, по умолчанию
		// Что будет, если вернуть масив?
		// return array('question' => $question, 'answer' => $answer);
    };