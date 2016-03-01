<?php

/**
 * FIXME сделать, чтобы НЕ редактировался существующий заказ при клонировании
 * FIXME сделать, чтобы , когда количество продукта меняется на 0, он автоматически удалялся
 *
 * При переходе на страницу клонирования копировать текущий заказ в сессию
 * и изменять продукты в сессии, а не продукты на исходном ордере
 * По нажатию на кнопку Клонировать, сессию очищать
 *
 * TODO если по нажатию на Клонировать нет продуктов в ордере, выдать исключение
 *
 * TODO Адрес доставки и прочую инфу можно сохранять в local storage
 */

namespace Application;

use Bluz;
use Application\Admin;

$_this = $this;

return

    /**
     * @param view
     * @param module
     * @param controller
     * @param _this
     * @param int $orders_id
     */
    function ($orders_id = null) use ($view, $module, $controller, $_this) {

        $user     = app()->getAuth()->getIdentity();
        $users_id = $user->id;

        $grid = new \Application\Orders\SqlGrid(['users_id' => $users_id]);
        $grid->setModule($module);
        $grid->setController($controller);

        $filters = array(
            'orders_id' => 'Id заказа',
            //'date_added' => 'Дата', TODO преобразовывать дату перед поиском
            'address'   => 'Адрес',
            'total'     => 'Сумма',
            'notes'     => 'Заметки',
        );

        $field = 'Выбрать поле';
        $value = '';
        $type  = $grid::FILTER_LIKE;

        $now_filters = $grid->getFilters();
        foreach ($now_filters as $column => $filter) {
            foreach ($filters as $allowed_filter => $title) {
                if ($allowed_filter == $column) {
                    $field = $filters[$column];

                    foreach ($filter as $filter_type => $filter_val) {
                        $value = $filter_val;
                        $type  = $filter_type;
                    }
                    break;
                }
            }
        }

        $view->grid    = $grid;
        $view->filters = $filters;
        $view->field   = $field;
        $view->value   = $value;
        $view->type    = $type;


    };
