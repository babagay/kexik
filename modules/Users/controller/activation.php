<?php
/**
 * User Activation
 *
 * @category Application
 *
 * @author   Anton Shevchuk
 * @created  05.12.12 15:17
 */
namespace Application;

use Application\Roles;
use Application\Roles\Table;
use Application\Users;
use Application\UsersActions;
use Application\UsersRoles;

return
/**
 *
 * @param int $id User UID
 * @param string $code
 * @return \closure
 */
function ($id, $code = null) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    $Id = false;

    if(is_array($id)){
        $Id = $id[0];
        if(isset($id[1]))
            $code = $id[1];
    }

    if($Id !== false)
        $id = $Id;

    // [!] В т.е users_actions столбец token назывался code
    // $actionRow = UsersActions\Table::findRow(array('userId' => $id, 'token' => $code));
    $actionRow = UsersActions\Table::findRow(array('userId' => $id, 'code' => $code));

    if (!$actionRow) {
        $this->getMessages()->addError('Invalid activation code');
        $this->redirectTo('index', 'index');
        return false;
    }

    $datetime1 = new \DateTime(); // now
    $datetime2 = new \DateTime($actionRow->expired);
    $interval = $datetime1->diff($datetime2);

    if ($actionRow->action !== UsersActions\Table::ACTION_ACTIVATION) {
        $this->getMessages()->addError('Invalid activation code');
    } elseif ($interval->invert) {
        $this->getMessages()->addError('The activation code has expired');
        $actionRow->delete();
    } else {
        // change user status
        $userRow = Users\Table::findRow($id);
        $userRow -> status = Users\Table::STATUS_ACTIVE;
        $userRow -> save();

        // create user role
        // get member role
        $roleRow = Roles\Table::findRowWhere(array('name' => Table::BASIC_MEMBER));
        //$roleRow = \Core\Model\Roles\Table::findRowWhere(array('name' => Table::BASIC_MEMBER));
        // create relation user to role
        $usersRoleRow = new UsersRoles\Row();
        $usersRoleRow->roleId = $roleRow->id;
        $usersRoleRow->userId = $userRow->id;
        $usersRoleRow->save();

        // remove old code
        $actionRow->delete();
        $this->getMessages()->addSuccess(
            'Your Account has been successfully activated. <br/>'.
            'You can now log in using the username and password you chose during the registration.'
        );
        $this->redirectTo('users', 'signin');
    }
    $this->redirectTo('index', 'index');
    return false;
};
