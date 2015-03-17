<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Auth;

use Bluz\Db\Row;

/**
 *
 */
abstract class AbstractRowEntity extends Row implements EntityInterface
{
    protected $selection;

    /**
     * Get roles
     *
     * @return array
     */
    abstract public function getPrivileges();

    /**
     * Can entity login
     *
     * @throws AuthException
     * @return boolean
     */
    abstract public function tryLogin();

    /**
     * Has role a privilege
     *
     * @param string $module
     * @param string $privilege
     * @return boolean
     */
    public function hasPrivilege($module, $privilege)
    {
        $privileges = $this->getPrivileges();

        return in_array($module.':'.$privilege, $privileges);
    }

    function getSelection()
    {
        return $this->selection;
    }

    /**
     * Login
     * @throw AuthException
     */
    public function login()
    {
        $this->tryLogin();
        app()->getAuth()->setIdentity($this);
    }

    /**
     * @param $name
     * @param $params
     * Если передавать $params, например, $r->getName(2), то он станет элементом массива: array('0'=> 2)
     */
    function __call($name,$params){

        $get = substr($name,0,3) ;

        // $name тгтерпретируется как ключ массива $date
        // Вызов: get<Имя_столбца>()
        if($get == 'get'){
            // вырезать get
            $name = str_replace($get,'',$name);
            $name = lcfirst($name);

            if(isset($this->data[$name]))
                // вернуть поле $name
                return $this->data[$name];
        }

        //TODO: другие интерпретации $name


    }
}
