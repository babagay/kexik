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



            /*

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
			
			
			


            if($headers === false)
                throw new \Bluz\Application\Exception\ApplicationException("Ресурс не найден",404);

            if(is_array($headers) AND count($headers) == 0)
                throw new \Bluz\Application\Exception\ApplicationException("Нет статей",404);

            if(!is_null($группа)){
                $имя_группы_для_крошек = $группа;
            }

			
            $view->articles = $headers;
*/

            return 'products.phtml';


        };