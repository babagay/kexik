<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Auth;

use Application\Exception;
use Application\Users;
use Bluz\Application;

use Bluz\Auth\AuthException;

/**
 * Auth Table
 *
 * @category Application
 * @package  Auth
 *
 * @author   Anton Shevchuk
 * @created  12.07.11 15:28
 */
class Table extends \Bluz\Db\Table
{

    const TYPE_REQUEST = 'request';
    const TYPE_ACCESS = 'access';

    const PROVIDER_EQUALS = 'equals';
    const PROVIDER_LDAP = 'ldap';
    const PROVIDER_TWITTER = 'twitter';
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_VK = 'vk';

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'auth';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('provider', 'foreignKey');

    /**
     * getAuthRow
     *
     * @todo foreign key for equals provider is equal to user login -_- ?
     * @param $provider
     * @param $foreignKey
     * @return Row
     */
    public function getAuthRow($provider, $foreignKey)
    {
       // fb($provider); // vk | equals
       // fb($foreignKey ); // vk_uid | Медведь (userlogin)
        return self::findRow(array('provider' => $provider, 'foreignKey' => $foreignKey));
    }

    /**
     * check user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return Row
     */
    public function checkEquals($username, $password)
    {
        $authRow = $this->getAuthRow(self::PROVIDER_EQUALS, $username);

        if (!$authRow) {
            throw new AuthException("User not found");
        }

        /** @var \Bluz\Auth\Auth $auth */
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new Exception("Encryption function for 'equals' adapter is not callable");
        }

        // encrypt password
        $password = call_user_func($options['encryptFunction'], $password, $authRow->tokenSecret);

        if ($password != $authRow->token) {
            throw new AuthException("Wrong password");
        }

        // get auth row
        return $authRow;
    }

    /**
     * authenticate user by login/pass
     *
     * @param string $username
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return boolean
     */
    public function authenticateEquals($username, $password)
    {
        /** @var $user Users\Row */
        $authRow = $this->checkEquals($username, $password);

        // get user profile
        $user = Users\Table::findRow($authRow->userId);

        if(!is_object($user)) return false;

        // try to login
        $user->login();

        return true;
    }

    /**
     * authenticate user by login/pass
     *
     * @param Users\Row $user
     * @param string $password
     * @throws Exception
     * @throws AuthException
     * @return boolean
     */
    public function generateEquals($user, $password)
    {
        /** @var \Bluz\Auth\Auth $auth */
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);

        if (!isset($options['encryptFunction']) or
            !is_callable($options['encryptFunction'])
        ) {
            throw new Exception("Encryption function for 'equals' adapter is not callable");
        }




        // clear previous generated Auth record
        // works with change password
        $this->delete(
            array(
                'userId' => $user->id,
                'foreignKey' => $user->login,
                'provider' => self::PROVIDER_EQUALS,
                'tokenType' => self::TYPE_ACCESS
            )
        );

        // new auth row
        $row = new Row();
        $row->userId = $user->id;
        $row->foreignKey = $user->login;
        $row->provider = self::PROVIDER_EQUALS;
        $row->tokenType = self::TYPE_ACCESS;

        // generate secret
        $alpha = range('a', 'z');
        shuffle($alpha);
        $secret = array_slice($alpha, 0, rand(5, 15));
        $secret = md5($user->id . join('', $secret));
        $row->tokenSecret = $secret;

        // encrypt password and save as token
        $row->token = call_user_func($options['encryptFunction'], $password, $secret);

        $row->save();

        return $row;
    }

    /**
     * Генерирует и сохраняет токен для заданного юзером пароля, когда он регистрируется,
     * используя данные аккаунта vk.com
     *
     */
    function generateToken($userId, $vk_uid, $login, $password, $auth_type)
    {
        $row = new Row();
        $row->userId = $userId;

        /**
         * Для обычного юзера foreignKey - это логин, а provider = 'equals',
         * тогда пользователь сможет входить в систему путем ввода логина и пароля.
         * Для ВК-шного юзера provider = 'vk', а foreignKey, наверное, может содержать vk_uid,
         * тогда пользователь будет входить только через кнопочку VK.
         */
        if($auth_type == 1){
            // Юзер в контакте
            $provider = "vk";
            $foreignKey = $vk_uid;
        } else {
            // Локальный юзер
            $provider =  self::PROVIDER_EQUALS;
            $foreignKey = $login;
        }


        $row->foreignKey = $foreignKey;
        $row->provider = $provider;


        $row->tokenType = self::TYPE_ACCESS;


        //
        $auth = app()->getAuth();
        $options = $auth->getOption(self::PROVIDER_EQUALS);
        //$options = $auth->getOption("vk");

        // generate secret
        $alpha = range('a', 'z');
        shuffle($alpha);
        $secret = array_slice($alpha, 0, rand(5, 15));
        $secret = md5($userId . join('', $secret));
        $row->tokenSecret = $secret;

        /**
         * md5(md5($password) . $secret)
         *
         *
         */

        // encrypt password and save as token
        $row->token = call_user_func($options['encryptFunction'], $password, $secret);

        $row->save();

        return $row;
    }

}
