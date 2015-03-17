<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 * Это нотация, совпадающая с путем к файлу
 * Если нужна произвольная нотация, нужно добавить строку в autoload_classmap.php
 */
namespace Core\Model\Articles;
//namespace Application\ArticleGroups;

use Application\Exception;
use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;


class Row extends AbstractRowEntity
{

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
