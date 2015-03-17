<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Application\Helper;

use Bluz\Application\Exception\RedirectException;

return
    /**
     * redirect to url
     *
     * @param string $url
     * @throws RedirectException
     * @return void
     */
    function ($url) {

        if(is_array($url)){
            if(isset($url[0])) $url = $url[0];
        }


        // Кидаем исключение - это предпосылка для редиректа
        // Кто и где его перехватывает?

        throw new RedirectException($url);
    };
