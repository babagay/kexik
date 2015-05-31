<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.05.15
 * Time: 11:12
 *
 * Наследование от FileBase означает по цепочке наследование от AbstractAdapter
 */

namespace Bluz\Cache\StorageAdapter;

use Bluz\Cache\Adapter\FileBase;
use Bluz\Cache\Cache;
use Bluz\Cache\InvalidArgumentException;

class File extends FileBase
{

    function __construct($settings)
    {
        if (!isset($settings['cacheDir'])) {
            throw new InvalidArgumentException("FileBase adapters is required 'cacheDir' option");
        }
        $cacheDir = $settings['cacheDir'];
        $cacheDir = PATH_CACHE . "/" . $cacheDir;

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $settings['cacheDir'] = $cacheDir;

        parent::__construct($settings);
    }

    /**
     * Must be implemented in particular cache driver implementation
     * Actual  work for \Bluz\Cache\CacheInterface::get() goes here
     * @see \Bluz\Cache\CacheInterface::get()
     */
    protected function doGet($id)
    {
        $filename = $this->getFilename($id);

        if (!is_file($filename)) {
            return false;
        }

        $cacheEntry = unserialize(file_get_contents($filename));

        if ($cacheEntry['ttl'] !== Cache::TTL_NO_EXPIRY && $cacheEntry['ttl'] < time()) {
            return false;
        }

        return $cacheEntry['data'];
    }

    /**
     * Must be implemented in particular cache driver implementation
     * Actual  work for \Bluz\Cache\CacheInterface::add() goes here
     * @see \Bluz\Cache\CacheInterface::add()
     */
    protected function doAdd($id, $data, $ttl = Cache::TTL_NO_EXPIRY)
    {
        return parent::doAdd($id, $data, $ttl);
    }

    /**
     * Must be implemented in particular cache driver implementation
     * Actual  work for \Bluz\Cache\CacheInterface::set() goes here
     * @see \Bluz\Cache\CacheInterface::set()
     */
    protected function doSet($id, $data, $ttl = Cache::TTL_NO_EXPIRY)
    {
        if ($ttl > 0) {
            $ttl = time() + $ttl;
        }

        $fileName = $this->getFilename($id);
        $filePath = pathinfo($fileName, PATHINFO_DIRNAME);

        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $cacheEntry = array(
            'ttl'  => $ttl,
            'data' => $data
        );

        return file_put_contents($fileName, serialize($cacheEntry));
    }

    /**
     * Must be implemented in particular cache driver implementation
     * Actual  work for \Bluz\Cache\CacheInterface::contains() goes here
     * @see Bluz\Cache\CacheInterface::contains()
     */
    protected function doContains($id)
    {
        $filename = $this->getFilename($id);

        if (!is_file($filename)) {
            return false;
        }

        $cacheEntry = unserialize(file_get_contents($filename));

        return $cacheEntry['ttl'] === Cache::TTL_NO_EXPIRY || $cacheEntry['ttl'] > time();
    }

    protected function getFilename($id)
    {
        /*
        $path = implode(str_split(md5($id), 12), DIRECTORY_SEPARATOR);

        $path = $this->cacheDir . DIRECTORY_SEPARATOR . $path;

        return $path . DIRECTORY_SEPARATOR . $id . $this->extension;
        */
        return $this->cacheDir . DIRECTORY_SEPARATOR . md5($id) . $this->extension;
    }

}