<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Users;
use Zend\Mvc\Application;

/**
 * Table
 *
 * @category Application
 * @package  Users
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 17:36
 */
class Table extends \Bluz\Db\Table
{
    /**
     * Pending email verification
     */
    const STATUS_PENDING = 'pending';
    /**
     * Active user
     */
    const STATUS_ACTIVE = 'active';
    /**
     * Disabled by administrator
     */
    const STATUS_DISABLED = 'disabled';
    /**
     * Removed account
     */
    const STATUS_DELETED = 'deleted';
    /**
     * system user with ID=0
     */
    const SYSTEM_USER = 0;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    protected $prefix = TABLE_PREFIX;


    /**
     * Get user privileges
     * Перенес этот метод сюда из models\Privileges\Table.php
     *
     * @param integer $userId
     * @return array
     */
    public function getUserPrivileges($userId)
    {
        // Roles\Table::getInstance()->getUserRolesIdentity($userId);
        $roles = \Core\Model\Roles\Table::getInstance()->getUserRolesIdentity($userId);

        $stack = array();
        foreach ($roles as $roleId) {
            $stack = array_merge($stack, $this->getRolePrivileges($roleId));
        }

        // magic array_unique for multi array
        return array_unique($stack);

        // follow code is faster, but required record for every user in memcache
        // in other words, need more memory for decrease CPU load
        // for update
        /*
        $cacheKey = 'privileges:user:'.$userId;
        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = Db::getDefaultAdapter()->fetchColumn(
                "SELECT DISTINCT r.id, CONCAT(p.module, ':', p.privilege)
                FROM acl_privileges AS p, acl_roles AS r, acl_users_roles AS u2r
                WHERE p.roleId = r.id AND r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY module, privilege",
                array((int) $userId)
            );

            app()->getCache()->set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'privileges');
            app()->getCache()->addTag($cacheKey, 'user:'.$userId);
        }
        return $data;
        */
    }

    /**
     * Get user privileges
     * Перенес этот метод сюда из models\Privileges\Table.php
     *
     * @param integer $roleId
     * @return array
     */
    public function getRolePrivileges($roleId)
    {
        $cacheKey = 'privileges:role:'.$roleId;

        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = app()->getDb()->fetchColumn(
                "SELECT DISTINCT CONCAT(p.module, ':', p.privilege)
                FROM ".$this->prefix."acl_privileges AS p, ".$this->prefix."acl_roles AS r
                WHERE p.roleId = r.id AND r.id = ?
                ORDER BY module, privilege",
                array((int) $roleId)
            );

            app()->getCache()->set($cacheKey, $data, \Bluz\Cache\Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'privileges');
        }
        return $data;
    }
}
