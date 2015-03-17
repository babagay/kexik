<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Application\Helper;

use Bluz\Application\Application;

return
    /**
     * get current user
     *
     * @return \Bluz\Auth\AbstractEntity|null
     */
    function () {
        /** @var Application $this */
        $_this = Application::getInstance();

        return $_this->getAuth() ?
            $_this->getAuth()->getIdentity() :
            null;
    };
