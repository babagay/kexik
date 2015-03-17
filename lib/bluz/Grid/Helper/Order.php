<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Grid\Helper;

use Application\Exception;
use Bluz\Application\Application;
use Bluz\Grid;

$_this = $this;

return
    /**
     * @return string|null $url
     */
    function ($column, $order = null, $defaultOrder = Grid\Grid::ORDER_ASC, $reset = true) use($_this) {
        /**
         * @var Grid\Grid $this
         */

        $rewrite = null;
        $allow_orders =  $_this->getAllowOrders() ;
//fb($_this);
          try {
              if (!in_array($column, $allow_orders)) {
                  return null;
              }
          } catch (Exception $e){

          }

        $orders = $_this->getOrders();

        // change order
        if (null === $order) {
            if (isset($orders[$column])) {
                $order = ($orders[$column] == Grid\Grid::ORDER_ASC) ?
                    Grid\Grid::ORDER_DESC : Grid\Grid::ORDER_ASC;
            } else {
                $order = $defaultOrder;
            }
        }








        // reset ot additional sort collumn
        if ($reset) {
            $rewrite = array('orders' => array());
        } else {
            $rewrite = array('orders' => $orders);
        }

        $rewrite['orders'][$column] = $order;

        return $_this->getUrl($rewrite);



        /*
        $arr_save = $column;
        $col = false;
        $grid = null;

        if(is_array($column)){
            if(isset($column[0])) $col   = $column[0];
            if(isset($column[1])){
                if(is_a($column[1],'Core\Model\ArrayGrid')) $grid = $column[1];
                else                $order = $column[1];
                if(is_a($column[1],'Application\Test\SqlGrid')) $grid = $column[1];
            }
            if(isset($column[2])){
                if(is_a($column[2],'Core\Model\ArrayGrid')) $grid = $column[2];
                else $defaultOrder = $column[2];
                if(is_a($column[2],'Application\Test\SqlGrid')) $grid = $column[2];
            }
            if(isset($column[3])){
                if(is_a($column[3],'Core\Model\ArrayGrid')) $grid = $column[3];
                else $reset = $column[3];
                if(is_a($column[3],'Application\Test\SqlGrid')) $grid = $column[3];
            }
            if(isset($column[4])){
                $grid = $column[4];
            }
        }

        if($col !== false) $column = $col;

        if(is_a($order,'Application\Test\SqlGrid')){
            $grid = $order;
            $order = null;
        }
        if(is_a($defaultOrder,'Application\Test\SqlGrid')){
            $grid = $defaultOrder;
            $defaultOrder = Grid\Grid::ORDER_ASC;
        }
        if(is_a($reset,'Application\Test\SqlGrid')){
            $grid = $reset;
            $reset = true;
        }


        // -- FIXME ошибка с urlencode(). Проверить код


        if (!in_array($column, $grid->getAllowOrders())) {
            return null;
        }

        $orders = $grid->getOrders();

        // change order
        if (null === $order) {
            if (isset($orders[$column])) {
                $order = ($orders[$column] == Grid\Grid::ORDER_ASC) ?
                    Grid\Grid::ORDER_DESC : Grid\Grid::ORDER_ASC;
            } else {
                $order = $defaultOrder;
            }
        }

        // reset ot additional sort collumn
        if ($reset) {
            $rewrite = array('orders' => array());
        } else {
            $rewrite = array('orders' => $orders);
        }

        $rewrite['orders'][$column] = $order;

        return $grid->getUrl($rewrite);
        */
    };
