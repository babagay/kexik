<?php
/**
 * View User Profile
 *
 * @author   Anton Shevchuk
 * @created  01.09.11 13:15
 */
namespace Application;

use Bluz;
use Application\Users;

return
/**
 * @param int $id
 *
 * @privilege ViewProfile
 *
 * @return \closure
 */
function ($id = null) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    $app_object = app()->getInstance();

    $app_object->getLayout()->title('Профиль');

    // try to load profile of current user
    if (!$id && $app_object->getAuth()->getIdentity()) {
        $id = $app_object->getAuth()->getIdentity()->id;
    }

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($id); // Application\Users\Row
    $view_user = $view->user(); // Application\Users\Row  Bluz\View\View




    if (!$user) {
        throw new Exception('User not found', 404);
    } else {
        //$data = $user->getData();
        $authTable = Auth\Table::getInstance();
        $userAuth = $authTable::findRow(array("userId" => $id, "provider" => "vk"));
        if($userAuth)
            $view->is_vk_user = 1;
        else
            $view->is_vk_user = 0;


        $view->user = $user;
        $view->view_user = $view_user;
    }
};
