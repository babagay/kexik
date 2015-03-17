<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * Странно, но, крошки создаются через этот хелпер! Хотя, хелпер в Core/View тоже вызывается
 *
 * @namespace
 */
namespace Bluz\View\Helper;

use Bluz\View\View;

return
    /**
     * @param array $data
     * @return array|null
     */
    function (array $data = array()) {

        if(isset($data[0])) $data = $data[0];

    /** @var View $this */
    if (app()->hasLayout()) {
        $layout = app()->getLayout();
        if (sizeof($data)) {
            $layout->system('breadcrumbs', $data);
        } else {
            return $layout->system('breadcrumbs');
        }
    }
    return null;
    };
