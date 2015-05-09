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
return
    /**
     * @param integer $orders_id
     * @param string $operation
     * @param integer $products_id
     * @return \closure
     * @privilege Edit
     */
    function ($orders_id = null, $operation = null, $products_id = null) use ($view) {

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


        # Тело
        $crumbs_arr =  array(
             $view->ahref('Админка', array('admin', 'Base') ),
             $view->ahref('Заказы', array('admin', 'orders') ),
            __($self_name),
        );

        switch($operation){
            case 'delete':
                fb($products_id);
                break;
            default:
                $crudController = new Bluz\Controller\Crud();
                $crudController->setCrud(Application\Orders\Crud::getInstance());
                $order = $crudController();
                fb($crudController());
                break;
        }









        # Вывод
        $view->order = $order;
        $view->orders_id = $orders_id;
       // $view->total = $total;

        $view->title($self_name,"append"); // rewrite

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        if($is_ajax)
            $app_object->useLayout(false);
        else
            $app_object->useLayout('backend.phtml');

    };