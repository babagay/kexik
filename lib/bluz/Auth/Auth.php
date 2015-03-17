<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Auth;

use Bluz\Common\Options;

/**
 * Auth support
 *  - DB adapter
 *  - LDAP adapter
 *
 * @category Bluz
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 19:10
 */
class Auth extends Options
{
   // use Options;

    /**
     * setup identity
     *
     * @param EntityInterface $identity
     * @return Auth
     */
    public function setIdentity(EntityInterface $identity)
    {
        app()->getSession()->identity = $identity;
        return $this;
    }

    /**
     * return identity
     *
     * @return EntityInterface|null
     */
    public function getIdentity()
    {
        return app()->getSession()->identity;
    }

    /**
     * clear identity
     *
     * @return Auth
     */
    public function clearIdentity()
    {
        app()->getSession()->identity = null;
        return $this;
    }
}
