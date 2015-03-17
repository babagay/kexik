<?php
    /**
     * AJAX closure
     *
     * @author   Anton Shevchuk
     * @created  26.09.11 17:41
     * @return closure
	 
	   TODO 
	   Проверять, если запрос пришел НЕ через аджакс, блокировать или редиректить
	   Такую защиту имеет смысл ставить на все замыкания, обрабатываемые ссылками (ссылка содержит href)
	   
     */
    namespace Application;

    use Bluz;

    $_this = $this;

    return

        function ($articles_frame_counter = null, $frame = null, $uri = null ) use ($view,$_this) {
            /**
             * @var \Application\Bootstrap $this

             fb($view); //  Bluz\View\View
             fb($_this); //  Application\Bootstrap
             *
             * @var \Bluz\View\View $view
			 *
			 * Если здесь эхнуть var_dump($articles); 
			 * И потом вернуть шаблон  return 'getheaders.phtml'; 
			 * То ошибки не будет
             */

			if(app()->getInstance()->getRequest()->getHeader('X-Requested-With') != 'XMLHttpRequest'){
				//редирект
			}
			 
            $db =  app()->getDb();

            $группа = null;

            $where_group = null;
            $where_visible = null;



            $uri = app()->getInstance()->getRequest()->getURI($uri);

            if(is_array($uri)){
            // Определить группу
                if(count($uri) > 0){
                    for($i=1;$i<count($uri);$i++){
                        if( "группа" == \Bluz\Translator\Translator::translit($uri[$i]) ){
                            $группа = \Bluz\Translator\Translator::translit($uri[$i+1]);
                            $группа = str_replace("%20",' ',$группа);
                        }
                    }
                }
            }


            if($группа !== null) $where_group = " AND grp.name = '$группа'";


			
			
            // Сообщения
            /*
            if ($messages) {
                $_this->getMessages()->addNotice('Notice Text');
                $_this->getMessages()->addSuccess('Success Text');
                $_this->getMessages()->addError('Error Text');
            }

            $_this->getMessages()->addNotice('Method '. $_this->getRequest()->getMethod());
            */

            // $view->time = date( "Y-m-d H:i:s", time());

            // При вызове через аджакс для перезагрузки страницы нужно использовать
            // app()->useJson(true);
            // $_this->reload(); // вызывает Core/Helper/Reload

            // "Обычный" редирект.
            // Вызов через аджакс:
            // $_this->redirect('http://ya.ru'); // Не работает: просто затемненный экран и ошибка в консоли
            // При включенном  app()->useJson(true); перекидывает на ya.ru

            // $_this->redirect('Blog', 'Test'); // There is no closure in file modules/Blog/ajax/Blog.php
            // При включенном  app()->useJson(true); просто перегружает страницу

            // Редирект на какой-нибудь другой контроллер, выдающий аджакс.
            // При вызове аджаксом:
            // А: Запрашивает контент у babagay.ru/Blog/Test и возвращает его в исходную страницу
            // Б: С использованием app()->useJson(true); редиректит на адрес babagay.ru/Blog/Test
            // $_this->redirectTo('Blog', 'Test');

            // sleep(2); // задержка 2 сек


            // $frame = 7;

            $offset = ($articles_frame_counter  - 1) * $frame; // 1
            $limit = $frame;

            // TODO брать из параметра
            $order_by = "art.dateline";
            $order = "DESC";
			
			// 
			$Articles = new \Core\Articles( array('группа' => $группа, 'offset' => $offset, 'limit' => $limit, 'order_by' => $order_by, 'order' => $order) );

			$headers = $Articles->getHeaders(); 
			
			
			
			/*
            // Для обычных юзеров скрывать невидимые статьи c пометкой visibility = 0
            $can_edit = false;
            $user = app()->getAuth()->getIdentity();
            if(is_object($user))
                $can_edit = $user->hasPrivilege($module = 'article', $privilege = 'Edit');
            if($can_edit !== true){
                $where_visible = " AND  art.visibility = 1 ";
            }


            $запрос = "
                        SELECT art.*, grp.name group_name
                        FROM zoqa_articles art
                        LEFT JOIN zoqa_article_groups grp ON grp.groups_id = art.groups_id
                        JOIN (select t.articles_id FROM zoqa_articles t ORDER BY $order_by $order LIMIT $offset,$limit ) as tbl ON tbl.articles_id = art.articles_id

                        WHERE art.articles_id > 0
                        $where_group
                        $where_visible
            ";



            $articles = $db->fetchAll($запрос);
			*/

            if($headers === false)
                throw new \Bluz\Application\Exception\ApplicationException("Ресурс не найден",404);

            if(is_array($headers) AND count($headers) == 0)
                throw new \Bluz\Application\Exception\ApplicationException("Нет статей",404);

            if(!is_null($группа)){
                $имя_группы_для_крошек = $группа;
            }

			/*
            $tmp_articles = array();

            $ind = 0;
            
            // visibility = 1 - показывать, 0 - скрывать, 2 - ?            
            foreach($articles as $article){
                // if( (int)$article['visibility'] !== 1 ) continue;



                if( (string)$article['descriptor'] !== '' ){
                    $descriptor = $article['descriptor'];
                } else {
                    $descriptor =  $article['articles_id'];
                }
                $link = 'блог/статья/' . $descriptor;

                $intro = preg_replace('/[\\\]{1,100}/i','',$article['intro']);
                $intro = htmlspecialchars_decode( $article["intro"] );
                $intro = $_this->Filter($article["intro"],1);

                $tmp_articles[$ind]['articles_id'] = $article['articles_id'];
                $tmp_articles[$ind]['descriptor'] = $article['descriptor'];
                $tmp_articles[$ind]['groups_id'] = $article['groups_id'];
                $tmp_articles[$ind]['group_name'] = $article['group_name'];
                $tmp_articles[$ind]['title'] = $article['title'];
                $tmp_articles[$ind]['body'] = substr($article['body'],0,250);
                $tmp_articles[$ind]['dateline'] = $_this->Date((int)$article["dateline"]); //date( "Y-m-d H:i:s", $article["dateline"] );
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
			*/
			
            $view->articles = $headers;


            return 'getheaders.phtml';


        };