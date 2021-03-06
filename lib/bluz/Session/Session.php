<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Session;

use Bluz\Common\Options;

/**
 * Session
 *
 * @category Bluz
 * @package  Session
 *
 * @author   Anton Shevchuk
 * @created  11.07.11 19:19
 *
 * @property mixed MessagesStore
 * @property mixed identity
 */
class Session extends Options
{
   // use Options;

    /**
     * Session store instance
     * @var Store\AbstractStore
     */
    protected $store = null;

    /**
     * Session store name
     * @var string
     */
    protected $storeName = 'session';

    /**
     * Session store parameters
     * @var string
     */
    protected $storeSettings = array();

    /**
     * setStore
     *
     * @param string $store description
     * @return Session
     */
    public function setStore($store)
    {
        $this->storeName = $store;
        return $this;
    }

    /**
     * setSettings
     *
     * @param array $settings
     * @return Session
     */
    public function setSettings(array $settings)
    {
        $this->storeSettings = $settings;
        return $this;
    }

    /**
     * getStore
     *
     * @throws SessionException
     * @return Store\AbstractStore
     */
    public function getStore()
    {
        if (!$this->store) {
            // switch statement for $store
            switch ($this->storeName) {
                case 'array':
                    $this->store = new \Bluz\Session\Store\ArrayStore();
                    break;
                case 'session':
                default:
                    $this->store = new \Bluz\Session\Store\SessionStore();
                    break;
            }
            $this->store->setOptions($this->storeSettings);
        }

        return $this->store;
    }

    /**
     * __set
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->getStore()->__set($key, $value);
    }

    /**
     * __get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getStore()->__get($key);
    }

    /**
     * Isset Offset
     *
     * @param  mixed $key
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->getStore()->__isset($key);
    }

    /**
     * Unset Offset
     *
     * @param  mixed $key
     * @return void
     */
    public function __unset($key)
    {
        $this->getStore()->__unset($key);
    }
}
