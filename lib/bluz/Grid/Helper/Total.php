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
     *
     * $_this Bluz\Common\Helper
     */
    function ($grid = null) use($_this) {
    /**
     * @var Grid\Grid $this
     */

        /*
        if(is_null($grid)) $grid = new Grid();
        elseif(is_array($grid)) $grid = $grid[0];
        */


        return $_this->getData()->getTotal();
    };
