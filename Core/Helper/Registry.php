<?php

namespace Core\Helper;

final class Registry
{

    private static $instance;

    private $data = array();

    private function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return Registry|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * gets all registry
     *
     * @return array|null
     */
    public function getData()
    {
        return (isset($this->data) ? $this->data : NULL);
    }

    /**
     * Override the registry
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @return null
     */
    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : NULL);
    }

    /**
     * @param $key
     * @param $value
     * @return null
     */
    public function set($key, $value)
    {
        if (is_object($value)) {
            foreach ($this->data as $k => $v) {
                if ($v === $value) {
                    if (getenv('ZOQA_DEBUG'))
                        fb("Registry Item already exists. key:" . $key . " Value: $value | $k $v");
                    return null;
                }
            }
        }

        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Remove item from the regisry
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    function __set($name, $var)
    {
        $this->set($name, $var);
    }

    function __get($name)
    {
        $var = null;

        if ($this->has($name))
            $var = $this->get($name);

        return $var;
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Enforce singleton; disallow wakeup
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}