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
    function ($question = null, $asd = null, $вопрос) use ($view) {
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

        //  На какой урл редиректить?
        $user = app()->getAuth()->getIdentity();
        $access_is_open = $user->hasPrivilege($module = 'blog', $privilege = 'read');
        if($access_is_open !== true)
            $app_object->redirectTo('', '');



        ///fb($app_object->getRequest()->getParams());
        $params = $app_object->getRequest()->getParams();

        if(isset($params['group'])) $group_id = (int)$params['group']; else $group_id = null;
        if(is_null($group_id) OR (int)$group_id == 0){
            if( isset( $params['groups_id'] ) ) $group_id = (int) $params['groups_id']; else $group_id = NULL;
        }
        if(isset($params['operation'])) $operation = $params['operation']; else $operation = null;
        if(isset($params['name'])) $name = $params['name']; else $name = null;

        // Если передан параметр "операция"
        if(!is_null($operation)){
            switch($operation){
                case 'delete': // Удалить группу
                    if ($row = Core\Model\ArticleGroups\Table::findRow($group_id)) {
                        $row->delete();
                        $app_object->redirectTo('blog', 'Edit');
                        $app_object->getMessages()->addSuccess("Row was removed");
                    } else {
                        throw new Exception('Record not found');
                    }
                    break;
                case 'add': // Добавить/изменить группу

                    if(is_null($group_id)  OR (int)$group_id == 0){ // add
                        $row = new Core\Model\ArticleGroups\Row(array('name' => $name));
                        $row->save();
                    } else { // edit

                        $where = array('groups_id' => $group_id);

                        Core\Model\ArticleGroups\Table::update($params,$where);
                    }

                    //$app_object->redirectTo('blog', 'Edit');
                    break;
                case 'edit': // Изменить группу
                    if ($row = Core\Model\ArticleGroups\Table::findRow($group_id)) {

                        $view->group = $row->toArray();
                    } else {
                        throw new Exception('Record not found');
                    }
                    break;
            }
        }




        $app_object->getLayout()->title('Edit');

        // Вывод по умолчанию

        $res_1 = app()->getDb()->fetchAll("SELECT groups.*, ( select count(articles_id) from zoqa_articles art where art.groups_id = groups.groups_id ) num FROM zoqa_article_groups groups LIMIT 100");

        $view->groups = $res_1;


        /*
        $grid = new \Core\Model\ArrayGrid();
        $grid->setModule('blog');
        $grid->setController('Edit');
        $view->grid = $grid;
        */



		// change layout
		$app_object->useLayout('front_end.phtml');

		// Такая конструкция загрузит шаблон
		return 'Edit.phtml';
		// Test.phtml грузится и так, по умолчанию
		// Что будет, если вернуть масив?
		// return array('question' => $question, 'answer' => $answer);
    };