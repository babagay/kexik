<?php
/**
 * Example of Db\Relations
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  14.11.13 10:45
 */
namespace Application;

use Bluz\Db\Relations;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'DB Relations',
        )
    );

    Relations::addClassMap('pages', '\\Application\\Pages\\Table');
    Relations::addClassMap('users', '\\Application\\Users\\Table');

    Relations::setRelation('pages', 'userId', 'users', 'id');

    Relations::addClassMap('acl_roles', '\\Application\\Roles\\Table');
    Relations::addClassMap('acl_users_roles', '\\Application\\UsersRoles\\Table');

    Relations::setRelation('acl_users_roles', 'roleId', 'acl_roles', 'id');
    Relations::setRelation('acl_users_roles', 'userId', 'users', 'id');

    Relations::setRelations('acl_roles', 'users', array('acl_users_roles'));

    /* @var Pages\Row */
    $page = Pages\Table::findRow(5);

    $relations = Relations::getRelations('pages', 'users');

    $field = $relations['pages'];

    $key = $page->{$field};

    $result = Relations::findRelations('pages', 'users', array($key));

    var_dump($result);

    $user = Users\Table::findRow(1);

    $relations = Relations::getRelations('pages', 'users');

    $field = $relations['users'];

    $key = $user->{$field};

    $result = Relations::findRelations('users', 'pages', array($key));

    var_dump($result);

    $relationTable = Relations::getRelations('users', 'acl_roles');
    $relations = Relations::getRelations('users', current($relationTable));

    $field = $relations['users'];

    $key = $user->{$field};

    $result = Relations::findRelations('users', 'acl_roles', array($key));

    var_dump($result);
    return false;
};
