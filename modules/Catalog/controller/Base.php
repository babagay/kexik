<?php

return
    /**
     * @cache 2
     * @return \closure
     */
    function ($категория = null) use ($view) {
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

        $категория = null;


        // TODO протестить в инете
        $param_2 = $app_object->getRequest()->get(2);
        $param_3 = $app_object->getRequest()->get(3);
        $param_4 = $app_object->getRequest()->get(4);

        if($param_2 === null){
            // По умолчанию
            // Взять все категории


            return $app_object->dispatch('catalog','categories');
            //

            // вывести категории
        } else {
            // взять категорию
            $категория = $param_2;

            if($param_3 === null) {
            // взять подкатегории для каталог/фрукты
            // select from categories where parent_id = $категория[cat_id]
            // вывести подкатегории

                return $app_object->dispatch('catalog','categories', array('категория' => $категория));

            } else {

                if($param_4 === null) {
                //  взять пер вую страницу продуктов для каталог/фрукты/яблоки, если $param_3 - подкатегория
                // взять продукт, если $param_3 - продукт
                // вывести продукты или продукт


                    $подкатегория = $param_3;

                    $selectBuilder = $db
                        ->select('c.categories_id')
                        ->from('categories', 'c')
                        ->where("categories_seo_page_name = '$подкатегория'")
                        ->orWhere("categories_id = '$подкатегория'");
                    $end_category = $selectBuilder->execute();

                    if(isset($end_category[0]['categories_id'])){
                        // $param_3 это подкатегория?
                        // Да - Пытаемся взять продукты подкатегории
                        return $app_object->dispatch('catalog','products', array('категория' => $подкатегория));
                    } else {
                        // Такой подкатегории нет
                        // $param_3 это продукт? Если да, пытаемся взять продукт

                        $selectBuilder = $db
                            ->select('p.products_id')
                            ->from('products', 'p')
                            ->where("products_id = '$param_3'");
                        $product = $selectBuilder->execute();

                        if(isset($product[0]['products_id']))
                            return $app_object->dispatch('catalog','products', array('products_id' => $product[0]['products_id']));

                        $selectBuilder = $db
                            ->select('p.products_id')
                            ->from('products', 'p')
                            ->where("products_seo_page_name = '$param_3'");
                        $product = $selectBuilder->execute();

                        if(isset($product[0]['products_id']))
                            return $app_object->dispatch('catalog','products', array('products_id' => $product[0]['products_id']));

                        throw new \Bluz\Application\Exception\ApplicationException("Такого продукта нет",404);
                    }



                } else {
                // вывести продукт
                    return $app_object->dispatch('catalog','products', array('продукт' => $param_4));
                }
            }
        }
            // Если $param_2 - это товар - вывести страницу продукта (каталог/яблоко-зеленое)
            // На нее можно зайти и чеерз http://localhost/kex/продукт/самовар (localhost/kex/продукт/12)

            // Если $param_2 это категория и $param_3 = null, вывести список подкатегорий

            // Если $param_2 - подкатегория и $param_3 = null, вывести пер вую страницу продуктов

            // Если $param_2 - это id, оно трактуется как categories_id

            // [!] Или можно сделать урл : каталог/фрукты/яблоки/яблоко-зеленое
            //  if($param_3 === null) взять подкатегории - каталог/фрукты
            // if($param_3 != null AND $param_4 === null) взять пер вую страницу продуктов - каталог/фрукты/яблоки
            // if($param_4 != null) показать продукт - каталог/фрукты/яблоки/яблоко-зеленое

        // Если категория НЕ имеет подкатегорий



        // TODO действие по умолчанию




        return;




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