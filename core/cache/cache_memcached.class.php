/**
 * Clansuite Cache Handler for Memcached
 *
 * @link http://www.danga.com/memcached/
 *
 * @package clansuite
 * @subpackage cache
 * @category caches
 */
class Clansuite_Cache_Memcached implements Clansuite_Cache_Interface
{
    public $cache = null;

    function __construct()
    {
        # instantiate object und set to class
        $this->cache = new Memcache;

        # fetch configuration and connection data
        $config = clansuite_registry::getConfigurationStatic();

        # establish connection to the memchaded server
        $this->cache->connect($config['cache']['memcached_host'],
                              $config['cache']['memcache_port']);
    }

    /**
     * Writes Data by $key $value pair into the cache
     *
     * @param $key
     * @param $value
     */
    function __set($key, $value)
    {
        $this->cache->set($key, $value);
    }

    /**
     * Fetches Data by $key from the Memcache
     *
     * @param $key
     */
    function __get($key)
    {
        return $cache->get($key);
    }

    // apc_fetch
    function fetch($key)
    {
        return apc_fetch($key);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was succesfully cached, false on failure
     * @access public
     */
    function store($key, $data, $cache_lifetime)
    {
        return apc_store($key, $data, $cache_lifetime);
    }

    /**
     * Deletes a $key from the Memcache
     *
     * @param $key
     */
    function delete($key)
    {
        return $this->cache->delete($key);
    }

    /**
     * Delete_all flushes the Cache
     *
     * @return a flushed cache
     */
    function delete_all()
    {
        return $this->cache->flush;
    }

    /**
     * Display Memcached Usage Informations
     */
    function stats()
    {
        // get memory usage in bytes
        #$memcache->getStats()['bytes'];
    }
}