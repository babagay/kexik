<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Config;

//FIXME use меняем на  extends \Bluz\Common\Options и каментим use Options; в теле класса
//use Bluz\Common\Options;


/**
 * Config
 *
 * @category Bluz
 * @package  Config
 *
 * @author   Anton Shevchuk
 * @created  03.03.12 14:03
 */
class Config extends \Bluz\Common\Options
{
   // use Options;

    /**
     * @var array
     */
    protected $config;

    /**
     * Path to configuration files
     * @var string
     */
    protected $path;


    /**
     * setup path to configuration files
     *
     * @param $path
     * @throws ConfigException
     * @return self
     */
    public function setPath($path)
    {
        if (!is_dir($path)) {
            throw new ConfigException('Configuration directory is not exists');
        }
        $this->path = rtrim($path, '/');
    }

    /**
     * load
     *
     * @param string $environment
     * @throws ConfigException
     * @return bool
     */
    public function load($environment = null)
    {
        if (!$this->path) {
            throw new ConfigException('Configuration directory is not setup');
        }

        if( is_file($this->path . '/local.php') )
            $configFile = $this->path . '/local.php';


        else
            $configFile = $this->path . '/base.php';
var_dump($environment);die;

        if (!is_file($configFile) or !is_readable($configFile)) {
            throw new ConfigException('Configuration file is not found');
        }

        $this->config = require $configFile;

        if (null !== $environment) {
            $customConfig = $this->path . '/app.' . $environment . '.php';
            if (is_file($customConfig) && is_readable($customConfig)) {
                $customConfig = require $customConfig;
                $this->config = array_replace_recursive($this->config, $customConfig);
            }
        }
    }

    /**
     * __get
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return null;
        }
    }

    /**
     * __set
     *
     * @param $key
     * @param $value
     * @throws ConfigException
     * @return void
     */
    public function __set($key, $value)
    {
        throw new ConfigException('Configuration is read only');
    }

    /**
     * __isset
     *
     * @param $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->config[$key]);
    }

    /**
     * return configuration as array
     *
     * @param string|null $section of config
     * @param string|null $subsection of config
     * @throws ConfigException
     * @return array
     */
    public function getData($section = null, $subsection = null)
    {
        if (!$this->config) {
            throw new ConfigException('System configuration is missing');
        }

        if (null !== $section && isset($this->config[$section])) {
            if ($subsection
                && isset($this->config[$section][$subsection])
            ) {
                return $this->config[$section][$subsection];
            } else {
                if( !is_array($this->config[$section]) ){
                    //FIXME Тк в переменную $this->config[$section] попадало значение TRUE вместо массива, влепил костыль
                    return array($section => $this->config[$section]);
                }
                if($section == 'request'){
                // Override baseUrl from config
                // Force URL for local and web (universal)


                    $config = $this->config[$section];
                    if(isset($config['baseUrl']))   $config['baseUrl'] = PUBLIC_URL . "/";

                    return $config;
                }
                return $this->config[$section];
            }

        } elseif (null !== $section) {
            return null;
        } else {
            return $this->config;
        }
    }
}
