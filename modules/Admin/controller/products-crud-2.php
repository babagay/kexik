<?php

namespace Application;

use Application\Products;
use Bluz\Controller;

return function ($products_id = null) use ($view) {

    $crudController = new Controller\Crud();

    $crudController->setCrud(Products\Crud::getInstance());

    //$params = app()->getRequest()->getAllParams();

    $selected_manufacturers_id = "400";
    $selected_categories_id = "10";
    $manufacturers_id = null;
    $selected_filters_id = [];

    if(!is_null($products_id)){
        $product = Products\Table::getInstance()->find($products_id);

        if(isset($product[0])){
            $selected_manufacturers_id = (string)$product[0]->manufacturers_id;
        }

        $category = app()->getDb()->fetchRow("SELECT * FROM products_to_categories WHERE products_id = '$products_id'");
        if(isset($category['categories_id'])) $selected_categories_id = $category['categories_id'];

        $selected_filters_id = FiltersToProducts\Table::getInstance()->getFilters([$products_id]);
    }

    $view->manufacturers_select = app()->getView()->select("manufacturers_id",Manufacturers\Table::getInstance()->getManufacturers("id-name"),$selected_manufacturers_id,['id' => 'manufacturers_id'])    ;

    $view->categories_select = app()->getView()->select("categories_id",Categories\Table::getInstance()->getCategories("id-name-by-level",false),$selected_categories_id,['id' => 'categories_id'])    ;

    $view->filters_select = app()->getView()->select("filters_id[]", Filters\Table::getInstance()->getFilters(), $selected_filters_id, ['id' => 'filters_id', 'multiple' => 'multiple']);

    app()->useLayout(false);

    if (!app()->getRequest()->isPost()) {

         return $crudController();

    } else {
        // Вызов аяксом

        $crud = $crudController();

        if($crud === null)
            // При создании новой записи
            return $crud;

        // При возникновении ошибок
        return function() use($crud) { return $crud; };
    }
};