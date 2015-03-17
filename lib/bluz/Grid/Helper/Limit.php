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
    function ($limit = 25) use($_this) {
    /**
     * @var Grid\Grid $this
     */
    $rewrite['limit'] = (int)$limit;

        fb($limit);

    if ($limit != $_this->getLimit()) {
        $rewrite['page'] = 1;
    }

    return $_this->getUrl($rewrite);
    };
