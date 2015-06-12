<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.05.15
 * Time: 11:06
 */

namespace Bluz\Cache;

use Bluz\Cache\Adapter;
use Bluz\Common\Options;
use Bluz\Config\ConfigException;

/**
 * Class Storage
 * @package Bluz\Cache
 *
 * <code>
 *  $adapter->asd = "33";
 *
 *  if($adapter->asd){
 *      $adapter->addTag('asd','filters_id:141');
 *  }
 *
 *  $asd = $adapter->get('asd');
 *
 *  unset($adapter->asd);
 *
 *  $adapter->deleteByTag("filters_id:141");
 * </code>
 */
class Storage extends Options implements CacheInterface, TagableInterface
{

    /**
     * No expiry TTL
     */
    const TTL_NO_EXPIRY = 0;

    /**
     * @var Adapter\AbstractStorageAdapter
     */
    protected $storageAdapter = null;

    /**
     * @var null|file|db|cookie
     */
    protected $adapterType = null;

    /**
     * @var StorageAdapter\AbstractStorageAdapter
     */
    protected $adapter = null;

    /**
     * @var Adapter\AbstractAdapter
     */
    protected $tagAdapter = null;

    /**
     * @var string
     */
    protected $tagPrefix = '@:';

    function __construct()
    {

    }

    function setAdapterType($adapter_type)
    {
        $this->adapterType = $adapter_type;
    }

    function setAdapter(CacheInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = $this->initAdapter($this->options);
        }

        return $this->adapter;
    }

    /**
     * @param $settings
     * @return \Bluz\Cache\StorageAdapter\AbstractStorageAdapter|\Bluz\Cache\Adapter\AbstractAdapter
     * @throws CacheException
     */
    protected function initAdapter($settings)
    {
        if (is_string($this->adapterType)) {
            $adapterName = $this->adapterType;
        } elseif (isset($settings['name'], $settings['settings'])) {
            $adapterName     = $settings['name'];
            $adapterSettings = $settings['settings'];
        } else
            throw new CacheException("Storage Adapter can't initialize. Configuration is missed");

        $adapterSettings = $settings;

        $adapterName  = ucfirst($adapterName);
        $adapterClass = '\\Bluz\\Cache\\StorageAdapter\\' . $adapterName;

        $adapter = new $adapterClass($adapterSettings);

        $this->adapter = $adapter;

        return $adapter;
    }

    function __get($name)
    {
        $var = null;

        if ($this->contains($name))
            $var = $this->get($name);

        return $var;
    }

    function __set($name, $value)
    {
        $this->set($name, $value);
    }

    function __isset($name)
    {
        return $this->contains($name);
    }

    function __unset($name)
    {
        return $this->delete($name);
    }


    /**
     * Retrieve data from storage by identifier
     * @param string $id storage entry identifier
     * @return mixed
     */
    public function get($id)
    {
        return $this->getAdapter()->get($id);
    }

    /**
     * Put data into cache.
     * Overwrite cache entry with given id if it exists.
     * @param string $id cache entry identifier
     * @param mixed $data data to cache
     * @param int $ttl Time To Live in seconds 0 == infinity
     * @return boolean
     */
    public function set($id, $data, $ttl = Cache::TTL_NO_EXPIRY)
    {
        return $this->getAdapter()->set($id, $data, $ttl);
    }

    /**
     * Put data into cache.
     * Operation will fail if cache entry with given id already exists
     * @param string $id cache entry identifier
     * @param mixed $data data to cache
     * @param int $ttl Time To Live in seconds 0 == infinity
     * @return boolean
     */
    public function add($id, $data, $ttl = Cache::TTL_NO_EXPIRY)
    {
        return $this->getAdapter()->add($id, $data, $ttl);
    }

    /**
     * Test for cache entry existence
     * @param string $id cache entry identifier
     * @return boolean
     */
    public function contains($id)
    {
        return $this->getAdapter()->contains($id);
    }

    /**
     * Delete cache entry
     * @param string $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->getAdapter()->delete($id);
    }

    /**
     * Invalidate(delete) all cache entries
     * @return mixed
     */
    public function flush()
    {
        return $this->getAdapter()->flush();
    }

    public function getTagAdapter()
    {
        if (!$this->tagAdapter) {
            // create instance of new adapter
            if (isset($this->options['settings']['tagAdapter'])) {
                $this->tagAdapter = $this->initAdapter($this->options['settings']['tagAdapter']);
            } elseif ($adapter = $this->getAdapter()) {
                $this->tagAdapter = $adapter;
            } else {
                throw new CacheException("Tag Adapter can't initialize. Configuration is missed");
            }
        }

        return $this->tagAdapter;
    }

    /**
     * Add tag $tag for cache entry with $id identifier
     * @param string $id
     * @param string $tag
     * @return boolean
     */
    public function addTag($id, $tag)
    {
        $identifiers = array();
        $tag         = $this->tagPrefix . $tag;

        if ($this->getTagAdapter()->contains($tag)) {
            $identifiers = $this->getTagAdapter()->get($tag);
        }

        // array may contain not unique values, but I can't see problem here
        $identifiers[] = $id;
        fb($tag);

        return $this->getTagAdapter()->set($tag, $identifiers);
    }

    /**
     * Delete all cache entries associated with given $tag
     * @param string $tag
     * @return boolean
     */
    public function deleteByTag($tag)
    {
        // maybe it makes sense to add check for prefix existence in tag name
        $tag         = $this->tagPrefix . $tag;
        $identifiers = $this->getTagAdapter()->get($tag);

        if (!$identifiers) {
            fb($tag);

            return false;
        }

        foreach ($identifiers as $identifier) {
            $this->getAdapter()->delete($identifier);
        }

        $this->tagAdapter->delete($tag);

        return true;
    }
}