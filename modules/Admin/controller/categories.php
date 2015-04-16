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
     * Изучить вызов лайаут-хелпера $app_object->getLayout()->Usercategories_name();
     */

    return
        /**
         * @param string $question
         * @return \closure
         */
        function (  $asd = null ) use ($view) {
            /**
             * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
             * или так: app()->getRequest();
             * @var View $view
             */
            //$app_object = Application\Bootstrap::getInstance();
            // Альтернатива $_this = $this;
            $app_object = app()->getInstance();

            // $_this = $this; не работает здесь
            $app_object->getLayout()->breadCrumbs(
                array(
                    $view->ahref('Админка', array('admin', 'Base')),
                    'Управление категориями',
                )
            );


            // TODO
            $user = app()->getAuth()->getIdentity();

            if(!is_object($user))
                throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет",404);

            $access_is_open = $user->hasPrivilege($module = 'admin', $privilege = 'Management');
            if($access_is_open !== true)
            //    $app_object->redirectTo('', '');
                throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет",404);



            ///fb($app_object->getRequest()->getParams());
            $params = $app_object->getRequest()->getParams();

            if(isset($params['group'])) $categories_id = (int)$params['group']; else $categories_id = null;
            if(is_null($categories_id) OR (int)$categories_id == 0){
                if( isset( $params['groups_id'] ) ) $categories_id = (int) $params['groups_id']; else $categories_id = NULL;
            }
            if(isset($params['operation'])) $operation = $params['operation']; else $operation = null;
            if(isset($params['categories_name'])) $categories_name = $params['categories_name']; else $categories_name = null;

            // Если передан параметр "операция"
            if(!is_null($operation)){
                switch($operation){
                    case 'delete': // Удалить группу
                        if ($row = Core\Model\ArticleGroups\Table::findRow($categories_id)) {
                            $row->delete();
                            $app_object->redirectTo('blog', 'Edit');
                            $app_object->getMessages()->addSuccess("Row was removed");
                        } else {
                            throw new Exception('Record not found');
                        }
                        break;
                    case 'add': // Добавить/изменить группу

                        if(is_null($categories_id)  OR (int)$categories_id == 0){ // add
                            $row = new Application\Categories\Row(array('categories_name' => $categories_name,
                                                                       'categories_seo_page_name' => '',
                                                                       'date_added' => ' now() ',
                            ));
                            fb(3);
                            $row->save();

                        } else { // edit

                            $where = array('groups_id' => $categories_id);

                            Core\Model\ArticleGroups\Table::update($params,$where);
                        }

                        //$app_object->redirectTo('blog', 'Edit');
                        break;
                    case 'edit': // Изменить группу
                        if ($row = Core\Model\ArticleGroups\Table::findRow($categories_id)) {

                            $view->group = $row->toArray();
                        } else {
                            throw new Exception('Record not found');
                        }
                        break;
                }
            }




            $app_object->getLayout()->title('Категoрии', 'rewrite');

            // Вывод по умолчанию

            $query_default = "SELECT categories.*,
          ( select count(products_id) from products_to_categories p2c where categories.categories_id = p2c.categories_id ) num
           FROM categories
           LIMIT 100";

            $res_1 = app()->getDb()->fetchAll($query_default);

            $view->categories = $res_1;




            /*
            $grid = new \Core\Model\ArrayGrid();
            $grid->setModule('blog');
            $grid->setController('Edit');
            $view->grid = $grid;
            */



            // change layout
            $app_object->useLayout('backend.phtml');

            // Такая конструкция загрузит шаблон
            //return 'Edit.phtml';
            // Test.phtml грузится и так, по умолчанию
            // Что будет, если вернуть масив?
            // return array('question' => $question, 'answer' => $answer);
        };