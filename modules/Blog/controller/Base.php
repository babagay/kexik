<?php
/*
 * [trace] fb('Error message',FirePHP::TRACE);
 *
 * URL для вызова: http://127.0.0.1/zoqa/my/base/question/2+2
 *
 * TODO
 * Исправить вывод <title>
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

[url статьи]: http://babagay.ru/блог/статья/102

   $now = gmdate( "D, d M Y H:i:s" );
 */
return
    /**
     * @param string $question
     * @return \closure
     */
    function ($статья = null, $группа = null) use ($view) {
        /**
         * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
         * или так: app()->getRequest();
         * @var View $view - Bluz\View\View
         */

        ///fb($app_object->getRequest()->getParams());
        ///fb($статья);
        ///$app_object = Application\Bootstrap::getInstance();

        $app_object = app()->getInstance();

        $articles_id = null;
        $article = null;
        $articles = null;
        $descriptor = null;
        $where_group = null;
        $имя_группы_для_крошек = null;
        $edit_link = false;
        $title = "Статьи";

        $db =  app()->getDb();



        if(!is_null($статья)){
            $int_article = (int)$статья;

            if((string)$int_article === $статья)
                // статья передана через id
                $articles_id = $статья;
            else
                $descriptor = $статья;
        }

        if(!is_null($группа)){
            // Вывести статьи заданной группы

            $группа = str_replace('%20',' ', $группа);
            // TODO: Проверить, если такой группы нет, перекинуть на 404
            // Если такой группы нет, $группа = null
            // $группа должна быт ьвсегда текстовой?
            $where_group = " AND grp.name = '$группа'";
        }





        // ВЫВОД
        if(!is_null($articles_id)){
        // Вывести конкретную статью по id

            // [!] Вернет то, что в первом столбце
            // $article = app()->getDb()->fetchOne("SELECT * FROM articles WHERE articles_id = $articles_id");
            $article = $db->fetchRow("SELECT * FROM articles WHERE articles_id = ". $db->quote($articles_id) );

            if( !$article ){
                throw new \Bluz\Application\Exception\ApplicationException("Такой статьи у меня нет",404);
            }

            $can_edit = false;
            $user = app()->getAuth()->getIdentity();
            if(is_object($user))
                $can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');
            if($can_edit === true)
                $edit_link = "blog/Articles/articles_id/{$article["articles_id"]}/operation/edit";

            //$article["intro"] = preg_replace('/[\\\]{1,100}/i','',$article['intro']);
            //$article["body"] = preg_replace('/[\\\]{1,100}/i','',$article['body']);

            //$article["body"] = htmlspecialchars_decode( $article["body"] );

            $can_edit = false;
            $user = app()->getAuth()->getIdentity();
            if(is_object($user))
                $can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');

            if( (int)$article["visibility"] !== 1 AND $can_edit !== true )
                throw new \Bluz\Application\Exception\ApplicationException("Такой статьи у меня нет",404);

            $article["intro"] = $app_object->Filter($article["intro"],1);
            $article["body"] = $app_object->Filter($article["body"],1);


            $article["dateline"] = $app_object->Date((int)$article["dateline"]);
            $article["group_name"] = Core\Model\ArticleGroups\Table::findRow($article["groups_id"])->getName();

            $имя_группы_для_крошек = $article["group_name"];

            //TODO: вытаскивать каменты

            /* Пример:
             * Есть новостной блоггерный сайт. Есть такие сущности как новости и комментарии к ним.
Задача — нужно написать запрос, который выводит список из 10 новостей определенного типа
(задается пользователем) отсортированные по времени издания в хронологическом порядке,
а также к каждой из этих новостей показать не более 10 последних коментариев, т.е. если коментариев больше — показываем только последние 10.
Все нужно сделать одним запросом.

[Решение]
select *
  from (select * from news order by date desc limit 10) n
  join comments c on (c.news_id = n.id)
 where c.id in (
     select id from comments c1 where c1.news_id = n.id order by c1.date desc limit 10
 )
 order by n.date desc, c.date desc
             */
            if(  (int)$article["visibility"] !== 1 AND $can_edit !== true ){
                $article = null;

                //TODO: редирект на 404
            }

            $app_object->getLayout()->title($article["title"]);

            $view->visibility = (int)$article["visibility"];

        } elseif(!is_null($descriptor)){
            // Вывести статью по дескриптору

            $article = $db->fetchRow("SELECT * FROM articles WHERE descriptor = ". $db->quote(urldecode($descriptor)) );

            if( !$article ){
                throw new \Bluz\Application\Exception\ApplicationException("Такой статьи у меня нет",404);
            }

            $can_edit = false;
            $user = app()->getAuth()->getIdentity();
            if(is_object($user))
                $can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');
            if($can_edit === true)
                $edit_link = "blog/Articles/articles_id/{$article["articles_id"]}/operation/edit";

            $can_edit = false;
            $user = app()->getAuth()->getIdentity();
            if(is_object($user))
                $can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');

            if( (int)$article["visibility"] !== 1 AND $can_edit !== true )
                throw new \Bluz\Application\Exception\ApplicationException("Такой статьи у меня нет",404);


            $article["dateline"] = $app_object->Date((int)$article["dateline"]);
            $article["group_name"] = Core\Model\ArticleGroups\Table::findRow($article["groups_id"])->getName();

            $article["intro"] = preg_replace('/[\\\]{1,100}/i','',$article['intro']);
            $article["body"] = preg_replace('/[\\\]{1,100}/i','',$article['body']);

            $article["body"] = htmlspecialchars_decode( $article["body"] );

            $article["body"] = $app_object->Filter($article["body"],1);
            $article["intro"] = $app_object->Filter($article["intro"],1);

            $имя_группы_для_крошек = $article["group_name"];

            $app_object->getLayout()->title($article["title"]);

            if($article["images"] != ''){
                $images = explode(';',$article["images"]);
                $article["images"] = $images;
            }

            //TODO: передать comments_num и comments

            if(  (int)$article["visibility"] !== 1 AND $can_edit !== true ){
                $article = null;

                // TODO если не админ, тогда редирект
                // throw new \Bluz\Application\Exception\ApplicationException("Такой статьи у меня нет",404);
            }

            $view->visibility = (int)$article["visibility"];

        } else {
            // Вывод заголовков статей

            //$r = new Core\Model\Articles\Table();

            // TODO: впилить логику вытаскивания статей в \Core\Articles
           $Articles = new \Core\Articles( array('asd' => 2) );
            // fb( $Articles->asd );


            $frame = 4;

            $offset = 0;
            $limit = $frame;

            $order_by = "t.dateline";
            $order = "DESC";

            $articles = $db->fetchAll("
                        SELECT art.*, grp.name group_name
                        FROM articles art
                        LEFT JOIN article_groups grp ON grp.groups_id = art.groups_id
                        JOIN (select t.articles_id FROM articles t ORDER BY $order_by $order LIMIT $offset,$limit ) as tbl ON tbl.articles_id = art.articles_id

                        WHERE art.articles_id > 0
                        $where_group


                        ");

            if($articles === false)
                throw new \Bluz\Application\Exception\ApplicationException("Ресурс не найден",404);

            if(is_array($articles) AND count($articles) == 0){
                // Число статей = 0

                // Убеждаемся, что такой группы нет и кидаем исключение
                $r = new Core\Model\ArticleGroups\Row(array('name' => $группа));
                /**
                 * $s = $r->getSelection();
                 * $s[0]->getName(); - взять имя группы
                 */
                if($r->getSelection() === null){
                    throw new \Bluz\Application\Exception\ApplicationException("Такой группы у меня нет",404);
                }
            }

            if(!is_null($группа)){
                $имя_группы_для_крошек = $группа;
            }

            $tmp_articles = array();

            $ind = 0;
            /**
             * visibility = 1 - показывать, 0 - скрывать, 2 - ?
             *
             */
            foreach($articles as $article){
                if( (int)$article['visibility'] !== 1 ) continue;



                if( (string)$article['descriptor'] !== '' ){
                    $descriptor = $article['descriptor'];
                } else {
                    $descriptor =  $article['articles_id'];
                }
                $link = 'блог/статья/' . $descriptor;

                $intro = preg_replace('/[\\\]{1,100}/i','',$article['intro']);
                $intro = htmlspecialchars_decode( $article["intro"] );
                $intro = $app_object->Filter($article["intro"],1);

                $tmp_articles[$ind]['articles_id'] = $article['articles_id'];
                $tmp_articles[$ind]['descriptor'] = $article['descriptor'];
                $tmp_articles[$ind]['groups_id'] = $article['groups_id'];
                $tmp_articles[$ind]['group_name'] = $article['group_name'];
                $tmp_articles[$ind]['title'] = $article['title'];
                $tmp_articles[$ind]['body'] = substr($article['body'],0,250);
                $tmp_articles[$ind]['dateline'] = $app_object->Date((int)$article["dateline"]); //date( "Y-m-d H:i:s", $article["dateline"] );
                $tmp_articles[$ind]['visibility'] = $article['visibility'];
                $tmp_articles[$ind]['cover'] = $article['cover'];
                $tmp_articles[$ind]['images_icons'] = $article['images_icons'];
                $tmp_articles[$ind]['link'] = $link;
                $tmp_articles[$ind]['intro'] =  $intro;
                $tmp_articles[$ind]['group_link'] = 'блог/группа/' . $article['group_name'];


                //$previous_year = time() - (12 * 30 * 24 * 60 * 60);
                //$previous_year = time() - (12 * 50 * 24 * 60 * 60);
                //fb($previous_year);

                $ind++;
            }

            unset($articles);
            $article = null;
            $articles = $tmp_articles;

            $view->frame = $frame;

            if( !is_null($группа) )
                $app_object->getLayout()->title($группа);
            else
                $app_object->getLayout()->title($title);
        }


        $view->article = $article;
        $view->articles = $articles;

		if($edit_link !== false)
            $view->edit_link = $edit_link;


		// change layout
		$app_object->useLayout('front_end.phtml');

		// Что будет, если вернуть масив?
        //return array('question' => $question, 'answer' => $answer);


        // Breadcrumbs (выводятся в ашаблоне шапки)

        // $view->breadCrumbs( array(0 => 'asd') ); // тоже работает

        if(isset($article['title'])) $article_name = $article['title'];
        else $article_name = '';


        if($имя_группы_для_крошек !== null){
            if( (string)$article_name === '' ) {
                $crumbs_arr =  array(
                    $view->ahref('Блог', array('блог', '') ),
                    $view->ahref($имя_группы_для_крошек, array('блог/группа/'.$имя_группы_для_крошек, '') ),
                );
            } else {
                $crumbs_arr =  array(
                    $view->ahref('Блог', array('блог', '') ),
                    $view->ahref($имя_группы_для_крошек, array('блог/группа/'.$имя_группы_для_крошек, '') ),
                    __($article_name)
                );
            }

            $app_object->getLayout()->breadCrumbs($crumbs_arr );




        } else {
            if( (string)$article_name === '' ) {
                $crumbs_arr =  array(
                    $view->ahref('Блог', array('блог', '') )
                );
            } else {
                $crumbs_arr =  array(
                    $view->ahref('Блог', array('блог', '') ),
                    __($article_name)
                );
            }

            $app_object->getLayout()->breadCrumbs($crumbs_arr);


        }


    };