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
    function ($grid = null) use($_this) {
        /**
         * @var Grid\Grid $this
         */

        /*
        if(!is_null($grid)){
            if(isset($grid[0])){
                $grid = $grid[0];
            }
        } else
            $grid = new Grid\Grid();
        */

        return $_this->getUrl(array());
    };
