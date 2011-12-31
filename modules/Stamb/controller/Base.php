<?php
return
    /**
     * @param string $question
     * @return \closure
     */
    function ($asd = null) use ($view) {
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
         *




         */

        # Инициализация
        $app_object = app()->getInstance();

        $db =  app()->getDb();

        $crumbs_arr = array();

        $is_ajax = false; // Запрос пришел не через аякс

        $self_name = "Stamb";

        # Тело
        $crumbs_arr =  array(
            // $view->ahref('Каталог', array('каталог', '') ),
            __('$category_parent'),
            __($self_name),
        );

        # Вывод
        $view->title($self_name,"append"); // rewrite

        $app_object->getLayout()->breadCrumbs($crumbs_arr);

        $app_object->useLayout('front_end.phtml');

    };