<?php
/**
 * Created by PhpStorm.
 * User: работа
 * Date: 30.06.15
 * Time: 14:27
 */

namespace Application;

use Bluz;
use Application\Admin;

/**
 * @var \Application\Bootstrap $_this
 */
$_this = $this;

/**
 * @var Bluz\View\View $view
 * @var String $module
 * @var String $controller
 *

 * TODO сделать , чтобы пересчитывался ордер при изменении количества продуктов
 * TODO сделать , чтобы пересчитывался ордер при удалении продукта
 */
return

    function ($orders_id = null,$products_id = null,$operation = null) use ($view, $module, $controller, $_this) {

        $options = array();

        ////$params = $_this->getRequest()->getParams();
        ////$get_params = $_this->getRequest()->get;

        if(!is_null($orders_id))
            $options['orders_id'] = $orders_id;

        $user = app()->getAuth()->getIdentity();

        if (app()->getRequest()->getMethod() == 'DELETE') {
            if (!is_null($orders_id) AND !is_null($products_id)) {
                app()->dispatch('my', 'order', [ 'products_id' => $products_id, 'operation' => 'delete-product', 'orders_id' => $orders_id]);
            }
        }

        $grid = new \Core\Model\Products\SqlGrid($options);
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


        $payment_types = PaymentTypes\Table::getInstance()->getPaymentTypes();

        // Способы оплаты
        $checkbox_group = '<h4>Оплата производится:</h4>';
        foreach ($payment_types as $payment_type) {
            $checkbox_group .= "<div>";
            $checkbox_group .= $view->radio('payment_types_id', $payment_type['payment_types_id'], false, []);
            $checkbox_group .= " <label for=\"payment_types_id\">{$payment_type['pay_by']}</label>";
            $checkbox_group .= "</div>";
        }

        $view->grid    = $grid;
        $view->filters = $filters;
        $view->field   = $field;
        $view->value   = $value;
        $view->type    = $type;
        $view->operation    = $operation;
        $view->orders_id    = $orders_id;
        $view->address_dostavki    = $user->delivery_address_1;
        $view->delivery_phone    = $user->phone;
        $view->delivery_date = app()->getDate()->now('d/m/Y H:i');
        $view->checkbox_group   = $checkbox_group;


    };
