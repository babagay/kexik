<?php
    /*
     * TODO: сделать тчобюы при добавлении статьи проверялся на уникальность дескриптор - перед внесением ва базу
     * В случае , если он не уникален, текст ихз текстарии Не должен потеряться
     *
     * Перевод сайта на UTF
     * // Кодировка страницы
        header('Content-Type: text/html; charset=UTF-8', true);

        // Локаль
        setlocale(LC_ALL, 'ru_RU.UTF-8', 'Russian_Russia.65001', 'UTF-8');

        // Кодировка на соединение с базой данных
        mysql_query('SET names "UTF8"');
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

        $app_object = app()->getInstance();

        // $_this = $this; не работает здесь
        $app_object->getLayout()->breadCrumbs(
            array(
                $view->ahref('Blog', array('blog', 'index')),
                'Статьи',
            )
        );






        // Acl
        $user = app()->getAuth()->getIdentity();

        if( !is_object($user)){
            throw new \Application\Exception("User не является объектом");
        }

        $access_is_open = $user->hasPrivilege($module = 'blog', $privilege = 'read');
        if($access_is_open !== true)
            $app_object->redirectTo('', '');

        $params = $app_object->getRequest()->getParams();


        ///fb($app_object->getRequest()->getParams());
        $params = $app_object->getRequest()->getParams();

        /*
        if(isset($params['group'])) $group_id = (int)$params['group']; else $group_id = null;
        if(is_null($group_id) OR $group_id == 0){
            if( isset( $params['groups_id'] ) ) $group_id = (int) $params['groups_id']; else $group_id = NULL;
        }
        if(isset($params['article'])) $articles_id = (int)$params['article']; else $articles_id = null;
        if(is_null($articles_id)) {
            if( isset( $params['articles_id'] ) ) $articles_id = (int) $params['articles_id']; else $articles_id = NULL;
        }
        */
        //fb($params);

        if(isset($params['groups_id'])) $groups_id = $params['groups_id']; else $groups_id = null;
        if(isset($params['articles_id'])) $articles_id = $params['articles_id']; else $articles_id = null;
        if(isset($params['operation'])) $operation = $params['operation']; else $operation = null;
        if(isset($params['title'])) $title = $params['title']; else $title = null;
        if(isset($params['body'])) $body = $params['body']; else $body = null;
        if(isset($params['article'])) $article = $params['article']; else $article = null;
        if(isset($params['sort'])) $sort = $params['sort']; else $sort = 'articles_id';
        if(isset($params['direction'])) $direction = $params['direction']; else $direction = 'asc';
        if(isset($params['descriptor'])) $descriptor = $params['descriptor']; else $descriptor = 'asc';
        if(isset($params['cover'])) $cover = $params['cover']; else $cover = null;
        if(isset($params['images'])) $images = $params['images']; else $images = null;
        if(isset($params['intro'])) $intro = $params['intro']; else $intro = null;
        if(isset($params['images_icons'])) $images_icons = $params['images_icons']; else $images_icons = null;
        if(isset($params['daterange'])) $daterange = $params['daterange']; else $daterange = null;

        // URL без сортировки
        $url_nonsort = '';
        if(!is_null($groups_id)) $url_nonsort .= "/group/$groups_id";
        if(!is_null($articles_id)) $url_nonsort .= "/article/$articles_id";
        if(!is_null($operation)) $url_nonsort .= "/operation/$operation";

        // Сформировать полный url с учетом фильтров
        // FIXME сделать чтобы в урле не накапливалось несколько лексемм operation
        $url = $url_nonsort;
        if(!is_null($sort)) $url .= "/sort/$sort";
        if(!is_null($direction)) $url .= "/direction/$direction";


        // УРЛ без операции
        $non_operation_url = preg_replace('/(operation\/[hide|unhide|delete|add|apply|edit|sort|add\-image|add\-multiimage]*\/)/i','',$url);



        // Взять все группы через Core хелпер
        $groups = $app_object->groups('all');



        // Если передан параметр "операция"
        if(!is_null($operation)){
            switch($operation){
                case 'hide': // Скрыть статью

                    $visibility = 0;
                    app()->getDb()->query("UPDATE zoqa_articles SET visibility = :visibility WHERE articles_id = :articles_id", array('visibility' => $visibility, 'articles_id' => $articles_id));
                    @$url = "/sort/$sort/direction/$direction";
                    $app_object->redirectTo('blog', 'Articles' . $url   );

                    break;
                case 'unhide': // Открыть статью
                    $visibility = 1;
                    app()->getDb()->query("UPDATE zoqa_articles SET visibility = :visibility WHERE articles_id = :articles_id", array('visibility' => $visibility, 'articles_id' => $articles_id));
                    @$url = "/sort/$sort/direction/$direction";

                    $app_object->redirectTo('blog', 'Articles' . $url  );

                    break;
                case 'delete': // Удалить статью
                    if ($row = Core\Model\Articles\Table::findRow($articles_id)) {
                        $row->delete();
                        $app_object->redirectTo('blog', 'Articles');
                        $app_object->getMessages()->addSuccess("Row was removed");
                    } else {
                        throw new Exception('Record not found');
                    }
                    break;
                case 'add': // Добавить статью/сохранить изменения
                    $dateline = time();

                    $day = null;
                    $mon = null;
                    $yer = null;
                    if( trim((string)$daterange) != '' ){
                        //fb($daterange);die;
                        $daterange = explode('-',$daterange);
                        @$day = $daterange[0];
                        @$mon = $daterange[1];
                        @$yer = $daterange[2];
                        if(!is_null($yer)) {
                            $dateline = mktime( 0, 0, 0, $mon, $day, $yer );
                        }
                    }

                    // Пример: echo date("M-d-Y", mktime(0, 0, 0, 1, 1, 98));


                    $body = preg_replace('/[\\\]{2,100}/i','',$body);
                    $body = preg_replace('/"/',"'",$body);

                    $intro = preg_replace('/[\\\]{2,100}/i','',$intro);
                    $intro = preg_replace('/"/',"'",$intro);

                    define('REPLACE_FLAGS', ENT_QUOTES );

                    $intro = htmlspecialchars($intro,REPLACE_FLAGS,'UTF-8');

                    //$body = htmlentities($body);
                    $body = htmlspecialchars($body,REPLACE_FLAGS,'UTF-8');


                    $arr = array(
                        'title'        => $title,
                        'body'         => $body,
                        'dateline'     => $dateline,
                        'groups_id'    => $groups_id,
                        'descriptor'   => $descriptor,
                        //'visibility' => 2,
                        'images'       => $images,
                        'cover'        => $cover,
                        'intro'        => $intro,
                        'images_icons' => $images_icons,
                    );

                    if((int)$articles_id > 0) {
                        // edit
                        $arr['articles_id'] = $articles_id;

                        // Дату оставить прежней
                        // unset($arr['dateline']);

                        // Вариант 1 - работает
                        // $table = Core\Model\Articles\Table::getInstance();
                        // $table->update($arr,array('articles_id' => $articles_id));

                        // Вариант 2
                        Core\Model\Articles\Table::update($arr,array('articles_id' => $articles_id));

                        /// Вариант 3 - Не работает, надо изучить
                        // app()->getDb()->update("zoqa_articles")->set('body',$body)->where("articles_id = $articles_id");

                        /// Альтернативный метод - Вариант 4
                        //app()->getDb()->query("UPDATE zoqa_articles SET title = :title, body = :body WHERE articles_id = :articles_id",
                        //    array('title' => $title, 'body' => $body, 'articles_id' => $articles_id));

                        /// fb( $table->getColumns() ); - взять все столбцы таблицы

                    } else {
                        $row = new Core\Model\Articles\Row($arr);
                        $row->save();
                    }

                    $app_object->redirectTo('blog', 'Articles');
                    break;
                case 'add-image':
                    // отключить view
                    // return function () {        };

                    // отключить layout
                    // FIXME : выдает ошибку
                    // SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data
                    //  response = conv( response );
                    // Response Text: <b
                    // В ответ откуда-то попадает текст <b
                    // Но работают всплывающие сообщения
                    // app()->getInstance()->useJson(true);

                    $name = $type = $tmp_name = $error = $size = null;

                    $Yandex = $app_object->getYandex();
                    $token = $Yandex->getToken();


                    $file = $app_object->getRequest()->files ;

                    if(isset($file['title-image'])){
                        $name = $file['title-image']['name'] ; // 3.jpg
                        $type = $file['title-image']['type'] ; // image/jpeg
                        $tmp_name = $file['title-image']['tmp_name'] ; // /tmp/phpLuQAI7
                        $error = $file['title-image']['error'] ; // 0
                        $size = $file['title-image']['size'] ;
                    }

                    if($error == UPLOAD_ERR_OK){
                        move_uploaded_file($tmp_name, "public/images/$name");
                    }

                    $picture =  getcwd() . "/public/images/$name" ;
                    $album = 451358;
                    $ahref = '';

                    if( file_exists($picture) ){
                        $image_id = $Yandex->setAlbum($album)->storePhoto($picture);
                        //fb($result);

                        //sleep(1);

                        // Получить ссылку на пиктограммку
                        //$image_id = 1044919;
                        if(!is_object($Yandex))
                            throw new \Bluz\Application\Exception\ApplicationException("Проблема при загрузке картинки",500);

                        $ahref = $Yandex->getPhotoInfo($image_id)->img->L->href ;

                        unlink($picture);
                    }

                    // задержка
                    //sleep(1);

                    return function () use ($ahref) {
                        header("Content-Type: application/json");
                        http_response_code(200);
                        //echo json_encode( array('asd' => 'zxc') );
                        return json_encode( array('ahref' => $ahref) );
                    };

                    break;
                case 'add-multiimage':
                    $name = $type = $tmp_name = $error = $size = null;

                    $Yandex = $app_object->getYandex();
                    $token = $Yandex->getToken();


                    $file = $app_object->getRequest()->files ;

                    if(isset($file['illustration'])){
                        $name = $file['illustration']['name'] ; // 3.jpg
                        $type = $file['illustration']['type'] ; // image/jpeg
                        $tmp_name = $file['illustration']['tmp_name'] ; // /tmp/phpLuQAI7
                        $error = $file['illustration']['error'] ; // 0
                        $size = $file['illustration']['size'] ;
                    }

                    if($error == UPLOAD_ERR_OK){
                        move_uploaded_file($tmp_name, "public/images/$name");
                    }

                    $picture =  getcwd() . "/public/images/$name" ;
                    $album = 451358;
                    $ahref = '';
                    $ahref_small = '';

                    if( file_exists($picture) ){
                        $image_id = $Yandex->setAlbum($album)->storePhoto($picture);

                        //sleep(1);

                        // Получить ссылку на пиктограммку
                        $ahref = $Yandex->getPhotoInfo($image_id)->img->XS->href ;
                        $ahref_small = $Yandex->getPhotoInfo($image_id)->img->M->href ;

                        unlink($picture);
                    }

                    return function () use ($ahref,$ahref_small) {
                        header("Content-Type: application/json");
                        http_response_code(200);
                        //echo json_encode( array('asd' => 'zxc') );
                        return json_encode( array('ahref' => $ahref, 'ahref_small' => $ahref_small) );
                    };

                    break;

                case 'apply': // Применить текущие изменения в режиме редактирования

                    return function() use($app_object) {

                        $error = '';
                        $response = 'error';

                       $daterange = $app_object->getRequest()->daterange ;
                       $title = $app_object->getRequest()->title ;
                       $articles_id = $app_object->getRequest()->articles_id ;
                       $descriptor = $app_object->getRequest()->descriptor ;
                       $groups_id = $app_object->getRequest()->groups_id ;
                       $intro = $app_object->getRequest()->intro ;
                       $cover = $app_object->getRequest()->cover ;
                       $images = $app_object->getRequest()->images ;
                       $images_icons = $app_object->getRequest()->images_icons ;
                       $body = $app_object->getRequest()->body ;

                        $dateline = time();

                        $day = $mon = $yer = null;
                        if( trim((string)$daterange) != '' ){
                            //fb($daterange);die;
                            $daterange = explode('-',$daterange);
                            @$day = $daterange[0];
                            @$mon = $daterange[1];
                            @$yer = $daterange[2];
                            if(!is_null($yer)) {
                                $dateline = mktime( 0, 0, 0, $mon, $day, $yer );
                            }
                        }
                        $body = preg_replace('/[\\\]{2,100}/i','',$body);
                        $body = preg_replace('/"/',"'",$body);

                        $intro = preg_replace('/[\\\]{2,100}/i','',$intro);
                        $intro = preg_replace('/"/',"'",$intro);

                        define('REPLACE_FLAGS', ENT_QUOTES );

                        $intro = htmlspecialchars($intro,REPLACE_FLAGS,'UTF-8');

                        //$body = htmlentities($body);
                        $body = htmlspecialchars($body,REPLACE_FLAGS,'UTF-8');


                        $arr = array(
                            'title'        => $title,
                            'body'         => $body,
                            'dateline'     => $dateline,
                            'groups_id'    => $groups_id,
                            'descriptor'   => $descriptor,
                            //'visibility' => 2,
                            'images'       => $images,
                            'cover'        => $cover,
                            'intro'        => $intro,
                            'images_icons' => $images_icons,
                        );
                        if((int)$articles_id > 0) {

                            $arr['articles_id'] = $articles_id;
                            $upd = Core\Model\Articles\Table::update( $arr, array( 'articles_id' => $articles_id ) );

                            if($upd) $response = "ok";
                        }


                        $result = array(
                            'error' => $error,
                            'response' => $response,
                        );

                        return  $result;
                    };

                    break;

                case 'edit': // Показать экран для изменения статьи


                    //$groups_id = null;

                    if ($row = Core\Model\Articles\Table::findRow($articles_id)) {
                        $article = $row->toArray();
                        $groups_id = $article['groups_id'];

                        $article["intro"] = $app_object->Filter($article["intro"],1);
                        $article["body"] = $app_object->Filter($article["body"],1);

                        $article["dateline"] = date('d-m-Y', $article["dateline"]);


                        $view->article =  $article;

                        $view->showarticle_link =  "блог/статья/{$article["descriptor"]}";

                        $view->apply_btn = 1;

                    } else {
                        throw new Exception('Record not found');
                    }

                    // [!] Здесь редирект мешает вывести форму с параметрами статьи
                    // $app_object->redirectTo('blog', 'Articles');



                    break;
            }
        }

        // Вызов view хелпера
        $groups_select = $view->select(
            "groups_id",
            $groups,
            $groups_id,
            array("name"=>"groups_id")
        );


        $app_object->getLayout()->title('Статьи');

        $where = '';
        $sorting = '';

        if(!is_null($articles_id) AND $operation == 'show'){
            // Вывод по умолчанию
            $where = "WHERE art.articles_id = $article";
        } elseif (!is_null($groups_id) AND $operation == 'show'){
            // Вывод статей по группам
            $where = "WHERE grp.groups_id = $groups_id";
        }

        // Сортировка
        if(!is_null($sort)){
            $sorting = "ORDER BY $sort $direction";
        }

        $query = "
            SELECT art.*, grp.name group_name
            FROM zoqa_articles art
            LEFT JOIN zoqa_article_groups grp ON grp.groups_id = art.groups_id
            $where
            $sorting
        ;";

        $articles = app()->getDb()->fetchAll($query);

        $tmp_articles = array();

        foreach($articles as $key => $value){
            //date('Y-m-d', 1427666399999)
            $value['dateline'] = date('Y-m-d',  $value['dateline'] );
            $tmp_articles[] = $value;
        }

        $articles = $tmp_articles;

        $view->articles = $articles;
        $view->operation = $operation;
        $view->sort_direction = $direction;
        $view->url_nonsort = $url_nonsort;
        $view->url = $url; // Текущий урл, например, содержит параметры сортиовки
        $view->non_operation_url = $non_operation_url;
        $view->groups_select = $groups_select;


		// change layout
		$app_object->useLayout('front_end.phtml');

		// Такая конструкция загрузит шаблон
		return 'Articles.phtml';
		// Test.phtml грузится и так, по умолчанию
		// Что будет, если вернуть масив?
		// return array('question' => $question, 'answer' => $answer);
    };