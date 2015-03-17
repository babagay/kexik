<?php
/**
 * Table
 *
 * @category Application
 * @package  Roles
 */
//namespace Application\Roles;
    namespace   Core\Model\Roles;

use Bluz\Cache\Cache;
use Bluz\Db\Db;

class Table extends \Bluz\Db\Table
{
    const BASIC_GUEST = 'guest';
    const BASIC_MEMBER = 'member';
    const BASIC_ADMIN = 'admin';

    private $prefix = TABLE_PREFIX ;
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'acl_roles';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('id');

    /**
     * @var array
     */
    protected $basicRoles = array('guest', 'member', 'admin');

    /**
     * Get all roles in system
     * FIXME : проверить работоспособность
     * @return array
     */
    public function getRoles()
    {
        return $this->fetch("SELECT * FROM ". TABLE_PREFIX .  $this->table." ORDER BY id");
    }

    /**
     * getBasicRoles
     * 
     * @return array
     */
    public function getBasicRoles()
    {
        return $this->basicRoles;
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     * @return array of rows
     */
    public function getUserRoles($userId)
    {
        $data = $this->fetch(
            "SELECT r.*
            FROM acl_roles AS r, acl_users_roles AS u2r
            WHERE r.id = u2r.roleId AND u2r.userId = ?",
            array($userId)
        );
        return $data;
    }

    /**
     * Get all user roles in system
     *
     * @param integer $userId
     * @return array of identity
     */
    public function getUserRolesIdentity($userId)
    {


        if(TABLE_PREFIX !== '')
            $this->prefix = TABLE_PREFIX . "_" ;

        $cacheKey = 'roles:user:'.$userId;
        if (!$data = app()->getCache()->get($cacheKey)) {
            $data = Db::getDefaultAdapter()->fetchColumn(
                "SELECT r.id
                FROM ". $this->prefix . $this->table. " AS r, ".$this->prefix.  "acl_users_roles AS u2r
                WHERE r.id = u2r.roleId AND u2r.userId = ?
                ORDER BY r.id ASC",
                array($userId)
            );
            app()->getCache()->set($cacheKey, $data, Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'roles');
            app()->getCache()->addTag($cacheKey, 'user:'.$userId);
        }
        return $data;
    }
}
