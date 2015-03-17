<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
//namespace Core\Model\Categories;
namespace Application\Categories;

use Application\Exception;
use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;


class Row extends AbstractRowEntity
{

    function __construct($params = null)
    {
        if(!is_null($params)){
           //$r = \Core\Model\Categories\Table::findWhere($params);
           $r = Table::findWhere($params);
           if($r)
               $this->selection = $r;
        }
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getPrivileges()
    {
        // TODO: Implement getPrivileges() method.
    }

    /**
     * Can entity login
     *
     * @throws AuthException
     * @return boolean
     */
    public function tryLogin()
    {
        // TODO: Implement tryLogin() method.
    }


}
