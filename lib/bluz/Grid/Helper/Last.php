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
     * @return string
     */
    function () use($_this) {
    /**
     * @var Grid\Grid $this
     */
    return $_this->getUrl(array('page' => $_this->pages()));
    };
