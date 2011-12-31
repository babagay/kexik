<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Acl;

use Bluz\Common\Options;

/**
 * Acl
 *
 * @category Bluz
 * @package  Acl
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 15:09
 */
class Acl extends Options
{
    //use Options;

    /**
     * Is allowed
     *
     * @param string $module
     * @param string $privilege
     * @return boolean
     */
    public function isAllowed($module, $privilege)
    {
        if ($privilege) {
            $user = app()->getAuth()->getIdentity();
            //fb($user);
            //fb($user->hasPrivilege($module, $privilege));

            if (!$user || !$user->hasPrivilege($module, $privilege)) {
                return false;
            }

            return true;
        }
        return false;
    }
}
