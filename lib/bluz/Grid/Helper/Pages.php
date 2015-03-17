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
     * @return integer
     */
    function ($grid = null) use ($_this) {
    /**
     * @var Grid\Grid $this
     *
     * $_this Core\Model\Test\SqlGrid
     */

        return ceil ( $_this->getData()->getTotal() / $_this->getLimit() );



        /*
         *
     if(is_null($grid)) $grid = new Grid();


     elseif(is_array($grid)) $grid = $grid[0];




    return ceil($grid->getData()->getTotal() / $grid->getLimit());
         */
    };
