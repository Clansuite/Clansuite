<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: cache.class.php 1813 2008-03-21 22:46:21Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

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
?>