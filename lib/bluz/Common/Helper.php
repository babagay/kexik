<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Common;

/**
 * Application
 *
 * @category Bluz
 * @package  Common
 *
 * @author   Anton Shevchuk
 * @created  18.07.12 14:46
 *
 * closure index Array
 */
class Helper
{
    /**
     * @var array
     */
    protected $helpers = array();

    /**
     * @var array
     */
    protected static $helpersPath = array();

    /**
     * Add helper path
     *
     * @param string $path
     * @return self
     */
    public function addHelperPath($path)
    {
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, self::$helpersPath)) {
            self::$helpersPath[] = $path;
        }

        return $this;
    }

    function getHelpersPath()
    {
        return self::$helpersPath;
    }

    /**
     * Set helpers path
     *
     * @param string|array $helpersPath
     * @return self
     */
    public function setHelpersPath($helpersPath)
    {
        if (is_array($helpersPath)) {
            foreach ($helpersPath as $path) {
                $this->addHelperPath((string)$path);
            }
        } else {
            $this->addHelperPath((string)$helpersPath);
        }
        return $this;
    }

    /**
     * Call
     *
     * @param string $method
     * @param array $args
     * @throws Exception
     * @return mixed
     */
    public function __call($method, $args)
    {
        // Call helper function (or class)
        if (isset($this->helpers[$method]) && is_callable($this->helpers[$method])) {
            return call_user_func_array($this->helpers[$method], $args);
        }

        // Try to find helper file
        foreach (self::$helpersPath as $helperPath) {
            $helperPath = realpath($helperPath . '/' . $method . '.php');
            //$helperPath = realpath($helperPath . '/' . ucfirst($method) . '.php');
            // fb ($helperPath) ; // /home/babagayr/public_html/lib/bluz/Application/Helper/Title.php
            if ($helperPath) {

                $helperInclude = include $helperPath;
                if (is_callable($helperInclude)) {

                    $this->helpers[$method] = $helperInclude;
                } else {
                    throw new \Exception("Helper '$method' not found in file '$helperPath'");
                }
                return $this->__call($method, $args);
            }
        }
        throw new \Exception("Helper '$method' not found for '" . __CLASS__ . "'");
    }
}
