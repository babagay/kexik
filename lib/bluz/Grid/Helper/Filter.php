<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Grid\Helper;

use Bluz\Application\Application;
use Bluz\Grid;

$_this = $this;

return
    /**
     * @return string|null $url
     */
    function ($column, $filter = null, $value = null, $reset = true) use($_this) {
        /**
         * @var Grid\Grid $this
         */

        $grid = $_this;

        $col  = false;

        if(is_array($column)){
            $col = $column[0];

            if(isset($column[1])){
                $filter = $column[1];
            }
            if(isset($column[2])){
                $value = $column[2];
            }
            if(isset($column[3])){
                    $reset = $column[3];

            }

            $column = $col;
        }
        // --

        if (!in_array($column, $grid->getAllowFilters()) &&
            !array_key_exists($column, $grid->getAllowFilters())
        ) {
            return null;
        }
        if (!$grid->checkFilter($filter)) {
            return null;
        }

        // reset filters
        if ($reset) {
            $rewrite = array('filters' => array());
        } else {
            $rewrite = array('filters' => $grid->getFilters());
        }

        $rewrite['filters'][$column][$filter] = $value;

        return $grid->getUrl($rewrite);
    };
