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

    /*
     * TODO: перевести функционал на использование groups_id вместо group
     * Изучить вызов лайаут-хелпера $app_object->getLayout()->Username();
     */

    return
        /**
         * @param string $question
         * @return \closure
         */
        function ($категория = null) use ($view) {
            /**
             * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
             * или так: app()->getRequest();
             * @var View $view
             */
            //$app_object = Application\Bootstrap::getInstance();
            // Альтернатива $_this = $this;
            $app_object = app()->getInstance();

            // $_this = $this; не работает здесь
            // breadCrumbs не выводится
            $app_object->getLayout()->breadCrumbs(
                array(
                    $view->ahref('Test', array('test', 'index')),
                    'Basic DB operations',
                )
            );

            $db =  app()->getDb();

            if($категория === null)
                 $categories = $db->fetchAll ("SELECT * FROM categories WHERE parent_id = '0' " );
            else {
                $selectBuilder = $db
                    ->select('c.categories_id')
                    ->from('categories', 'c')
                    ->where("categories_seo_page_name = '$категория'")
                ->orWhere("categories_id = '$категория'");
                $category_parent = $selectBuilder->execute();

                if(!isset($category_parent[0]['categories_id']))
                    throw new \Bluz\Application\Exception\ApplicationException("Такой категории нет",404);

                $category_parent_id = $category_parent[0]['categories_id'];

                $categories = $db->fetchAll ("SELECT * FROM categories WHERE parent_id = '$category_parent_id'   " );

                $view->category_parent = $категория;
            }


            $view->categories = $categories;


//fb($categories);



            // change layout
            $app_object->useLayout('front_end.phtml');

            // Такая конструкция загрузит шаблон
            // return 'Edit.phtml';
            // Test.phtml грузится и так, по умолчанию
            // Что будет, если вернуть масив?
            // return array('question' => $question, 'answer' => $answer);
        };