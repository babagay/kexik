<?php
/**
 * @var Вместо Application $this используй $app_object = Application\Bootstrap::getInstance();
 * или так: app()->getRequest();
 * @var View $view - Bluz\View\View
 *
 *  $selectBuilder = $db
->select('p.products_id')
->from('products', 'p')
->where("products_id = '$param_3'");
$product = $selectBuilder->execute();

if(isset($product[0]['products_id']))
return $app_object->dispatch('catalog','products', array('products_id' => $product[0]['products_id']));


throw new \Bluz\Application\Exception\ApplicationException("Такого продукта нет",404);
return $app_object->dispatch('catalog','products', array('продукт' => $param_4));

 */

//use \Application\Orders;

return
    /**
     * @param integer $orders_id
     * @param string $operation
     * @param integer $products_id
     * @param array $params
     * @return \closure
     * @privilege Edit
     */
    function ($orders_id = null, $operation = null, $products_id = null, $params = []) use ($view) {

        # Инициализация
        $app_object = app()->getInstance();

        $db =  app()->getDb();

        $crumbs_arr = array();

        $is_ajax = false;
        if($app_object->getRequest()->isXmlHttpRequest())
            $is_ajax = true;

        $self_name = "Редактирование заказа $orders_id";

        $params = app()->getRequest()->getParams()  ;
        $get_params = app()->getRequest()->get;

        $order = null;

        if(isset($get_params['operation'])){
            if(is_null($operation))
                $operation = $get_params['operation'];
        }
        if(isset($get_params['orders_id'])){
            if(is_null($orders_id))
                $orders_id = $get_params['orders_id'];
        }
        if(isset($get_params['products_id'])){
            if(is_null($products_id))
                $products_id = $get_params['products_id'];
        }

        # Тело
        $crumbs_arr =  array(
             $view->ahref('Админка', array('admin', 'Base') ),
             $view->ahref('Заказы', array('admin', 'orders') ),
            __($self_name),
        );

        switch($operation){
            case 'delete':
                // Удалить продукт из заказа
                $order = Application\Orders\Crud::getInstance()->readOne(['orders_id' => $orders_id]);
                $order->deleteProduct($products_id);
                return null;
                break;
            case 'add':
                // Добавить продукт
                $order = Application\Orders\Crud::getInstance()->readOne(['orders_id' => $orders_id]);
                $order->addProduct($products_id);
                return null;
                break;
            case 'update':
                // Изменить продукт
                $order = Application\Orders\Crud::getInstance()->readOne(['orders_id' => $orders_id]);
                $order->updateProduct($products_id,$params);
                return null;
                break;
            case 'update-order':
                // TODO Изменить параметры заказа
                fb('update-order');
                ///Application\Orders\Table::getInstance()->updateProduct($products_id, $params);
                return null;
                break;
            default:
                //$crudController = new Bluz\Controller\Crud();
                //$crudController->setCrud(Application\Orders\Crud::getInstance());
                //$order = $crudController();
                //fb($crudController());
                break;
        }



        # Вывод
        //$view->order = $order;
        $view->orders_id = $orders_id;
       // $view->total = $total;

        $view->title($self_name,"append"); // rewrite

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        if($is_ajax)
            $app_object->useLayout(false);
        else
            $app_object->useLayout('backend.phtml');

    };