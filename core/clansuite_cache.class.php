<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: cache.class.php 1813 2008-03-21 22:46:21Z vain $
    */

/**
 * Interfaces
 * for all Cachehandler Classes to implement
 *
 * @package clansuite
 * @subpackage session
 * @category interfaces
 */
interface Clansuite_Cache_Interface
{
    function fetch($key);
    function store($key, $data, $cache_lifetime);
    function delete($key);
    function stats();
}

/**
 * Clansuite Cache Handler for APC (Alternative PHP Cache)
 *
 * APC is a) an opcache and b) a memory based cache.
 *
 * 
 * @package clansuite
 * @subpackage cache
 * @category caches
 */
class Clansuite_Cache_APC implements Clansuite_Cache_Interface
{
    function __construct()
    {
        # Check if APC extension is loaded and set flag
        if( !defined('CSID_EXTENSION_LOADED_APC') )
        {
            define( 'CSID_EXTENSION_LOADED_APC', extension_loaded('apc') );
        }

        # Check for Flag
        if( CSID_EXTENSION_LOADED_APC == false)
        {
            new clansuite_exception($this, 'PHP Extension APC (Alternative PHP Cache) must be loaded!', 1);
        }

        // only get the data off the text file if we can't get it from APC i.e. from memory
        if( !(GID_EXTENSION_LOADED_APC && ($this->_keywords=apc_fetch('highlighter_keywords'))) );

        // if APC is loaded, store the data in memory
        if( GID_EXTENSION_LOADED_APC )
            apc_store( 'highlighter_keywords', $this->_keywords );
    }

    // apc_fetch
    function fetch($key)
    {
        return apc_fetch($key);
    }

    // apc_store
    function store($key, $data, $cache_lifetime)
    {
        return apc_store($key, $data, $cache_lifetime);
    }

    // apc_delete
    function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * Get stats and usage Informations for display from APC
     */
    function stats()
    {

    }
}

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
    $cache = null;

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
        $memcache->getStats()['bytes'];
    }
}
?>