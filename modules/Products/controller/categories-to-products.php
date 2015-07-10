<?php
    /**
     * Created by PhpStorm.
     * User: работа
     * Date: 02.07.15
     * Time: 19:05
     *
     * @param integer $categories_id
     * @param integer $products_id
     * @param string $operation
     * @return \closure
     * @privilege LinkProducts2Categories
     */

    use Application\Categories;

    $_this = $this;

    return
        function ($categories_id = null,$operation = null,$products_id = null) use ($view,$_this) {

            $is_ajax = false;
            if(app()->getRequest()->isXmlHttpRequest())
                $is_ajax = true;

            $params = app()->getRequest()->getParams()  ;
            $get_params = app()->getRequest()->get;

            // [!] не работает через аякс
            app()->getLayout()->breadCrumbs(
                array(
                    $view->ahref('Админка', array('admin', 'Base')),
                    $view->ahref('Управление категориями', array('admin', 'categories')),
                    'Привязка продуктов к категориям',
                )
            );

            if(isset($get_params['operation'])){
                if(is_null($operation))
                    $operation = $get_params['operation'];
            }
            if(isset($get_params['categories_id'])){
                if(is_null($categories_id)){
                    $categories_id = $get_params['categories_id'];
                }
            }
            if(isset($get_params['products_id'])){
                if(is_null($products_id))
                    $products_id = $get_params['products_id'];
            }

            $category = \Application\Categories\Table::findRow(array('categories_id' => $categories_id));

            if(!is_object($category))
                throw new \Application\Exception("Ошибка. Такой категории нет");

            if($is_ajax)
                app()->useLayout(false);
            else
                app()->useLayout('backend.phtml');

            switch($operation){
                case 'delete':
                    // Вынести продукт из категории
                    app()->getDb()->getDefaultAdapter()->delete('products_to_categories')
                        ->where('categories_id = ?', $categories_id)
                        ->andWhere('products_id = ?', $products_id)
                        ->execute();

                    app()->getFilterKeeper()->getStorage()->flush();

                    return null;
                    break;
                case 'add':
                    // Добавить продукт в категорию
                    $cats = app()->getDb()
                        ->select('p2c.categories_id,c.*')
                        ->from('products_to_categories', 'p2c')
                        ->where("products_id = '$products_id'")->leftJoin('p2c','categories','c','c.categories_id = p2c.categories_id')
                        ->execute();

                    if(is_array($cats)){
                        if(isset($cats[0]['categories_id'])){
                            foreach($cats as $cat){
                                if((int)$cat['categories_id'] == (int)$category->categories_id)
                                    throw new \Application\Exception("Продукт уже присоединён");
                            }
                            foreach($cats as $cat){
                                if((int)$cat['categories_level'] == (int)$category->categories_level)
                                    throw new \Application\Exception("Продукт уже входит в категорию <b>{$cat['categories_name']}</b>");
                            }
                        }
                    }

                    app()->getDb()
                        ->insert( 'products_to_categories' )
                        ->set( 'categories_id', $categories_id )
                        ->set( 'products_id', $products_id )
                        ->execute();

                    app()->getFilterKeeper()->getStorage()->flush();

                    return null;
                    break;
                default:
                    break;
            }

            $view->categories_id = $categories_id;
            $view->categories_name = $category->categories_name;
        };