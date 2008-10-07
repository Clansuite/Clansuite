<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
 * @link http://de3.php.net/manual/de/ref.apc.php
 *
 * @package clansuite
 * @subpackage cache
 * @category caches
 */
class Clansuite_Cache_APC implements Clansuite_Cache_Interface
{
    function __construct()
    {
        try
        {
            # Check if APC extension is loaded and set flag
            if( !defined('CSID_EXTENSION_LOADED_APC') )
            {
                define( 'CSID_EXTENSION_LOADED_APC', extension_loaded('apc') );
            }
    
            # Check for Flag
            if( CSID_EXTENSION_LOADED_APC == false)
            {
                throw new Exception('The PHP extension APC (Alternative PHP Cache) was not loaded! You should enable it in php.ini!', 300);
            }
        }
        catch (Exception $exception)
        {
            new clansuite_exception( $exception, 'Clansuite_Cache_APC __construct() failure. APC not loaded.');
        }

        /*
        // only get the data off the text file if we can't get it from APC i.e. from memory
        if( !(GID_EXTENSION_LOADED_APC && ($this->_keywords=apc_fetch('highlighter_keywords'))) );

        // if APC is loaded, store the data in memory
        if( GID_EXTENSION_LOADED_APC )
        {
            apc_store( 'highlighter_keywords', $this->_keywords );
        }*/
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

    // apc_delete
    function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * Get stats and usage Informations for display from APC
     * 1. Shared Memory Allocation
     * 2. Cache Infos / Meta-Data
     */
    function stats()
    {
        # Retrieve APC Version
        $apc_sysinfos['version'] = phpversion('apc');
        $apc_sysinfos['phpversion'] = phpversion();

        /**
         * ========================================================
         *   Retrieves APC's Shared Memory Allocation information
         * ========================================================
         */
        $apc_sysinfos['sma_info'] = apc_sma_info(); # set "false" for details

        # Calculate "APC Memory Size" (Number of Segments * Size of Segment)
        $apc_sysinfos['sma_info']['mem_size'] =  $apc_sysinfos['sma_info']['num_seg'] * $apc_sysinfos['sma_info']['seg_size'];

        # Calculate "APC Memory Usage" ( mem_size - avail_mem )
        $apc_sysinfos['sma_info']['mem_used'] =  $apc_sysinfos['sma_info']['mem_size'] - $apc_sysinfos['sma_info']['avail_mem'];

        # Calculate "APC Free Memory Percentage" ( mem_size*100/mem_used )
        $apc_sysinfos['sma_info']['mem_avail_percentage'] = sprintf("(%.1f%%)", $apc_sysinfos['sma_info']['avail_mem']*100/$apc_sysinfos['sma_info']['mem_size']);

        # Retrieves cached information and meta-data from APC's data store
        $apc_sysinfos['cache_info'] = apc_cache_info();
        $apc_sysinfos['cache_info']['cached_files'] = count($apc_sysinfos['cache_info']['cache_list']);
        $apc_sysinfos['cache_info']['deleted_files'] = count($apc_sysinfos['cache_info']['deleted_list']);

        /**
         * ========================================================
         *   System Cache Informations
         * ========================================================
         */
        $apc_sysinfos['system_cache_info'] = apc_cache_info('system', false); # set "false" for details

        # Calculate "APC Hit Rate Percentage"
        $total_hits = ($apc_sysinfos['system_cache_info']['num_hits'] + $apc_sysinfos['system_cache_info']['num_misses']);
        if($total_hits == 0) { $total_hits = 1;} # div by zero fix
        $hit_rate = $apc_sysinfos['system_cache_info']['num_hits'] * 100 / $total_hits;
        $apc_sysinfos['system_cache_info']['hit_rate_percentage'] = sprintf("(%.1f%%)", $hit_rate);

        # Calculate "APC Miss Rate Percentage"
        $apc_sysinfos['system_cache_info']['miss_rate_percentage'] = sprintf("(%.1f%%)", $apc_sysinfos['system_cache_info']['num_misses']*100/$total_hits);
        $apc_sysinfos['system_cache_info']['files_cached']  = count($apc_sysinfos['system_cache_info']['cache_list']);
        $apc_sysinfos['system_cache_info']['files_deleted'] = count($apc_sysinfos['system_cache_info']['deleted_list']);

        $time = time();
        # Request Rate (hits, misses) / cache requests/second
        $apc_sysinfos['system_cache_info']['req_rate']    = sprintf("%.2f",($apc_sysinfos['system_cache_info']['num_hits']+$apc_sysinfos['system_cache_info']['num_misses'])/($time-$apc_sysinfos['system_cache_info']['start_time']));
    	$apc_sysinfos['system_cache_info']['hit_rate']    = sprintf("%.2f",($apc_sysinfos['system_cache_info']['num_hits'])/($time-$apc_sysinfos['system_cache_info']['start_time']));
    	$apc_sysinfos['system_cache_info']['miss_rate']   = sprintf("%.2f",($apc_sysinfos['system_cache_info']['num_misses'])/($time-$apc_sysinfos['system_cache_info']['start_time']));
    	$apc_sysinfos['system_cache_info']['insert_rate'] = sprintf("%.2f",($apc_sysinfos['system_cache_info']['num_inserts'])/($time-$apc_sysinfos['system_cache_info']['start_time']));
        # size
        $apc_sysinfos['system_cache_info']['size_files']  = functions::getsize($apc_sysinfos['system_cache_info']['mem_size']);

        $apc_sysinfos['settings'] = ini_get_all('apc');

        /**
         * ini_get_all array mod: for each accessvalue
         * add the name of the PHP ACCESS CONSTANTS as 'accessname'
         */
        foreach( $apc_sysinfos['settings'] as $key => $value )
        {
           foreach( $value as $key2 => $value2 )
           {
               if( $key2 == 'access')
               {
                   # accessvalue => constantname
                   if( $value2 == '1' ){ $name = 'PHP_INI_USER';   }
                   if( $value2 == '2' ){ $name = 'PHP_INI_PERDIR'; }
                   if( $value2 == '4' ){ $name = 'PHP_INI_SYSTEM'; }
                   if( $value2 == '7' ){ $name = 'PHP_INI_ALL';    }

                   # add as accessname to the original array
                   $apc_sysinfos['settings'][$key]['accessname'] = $name;
                   unset($name);
               }
           }
        }

        #$apc_sysinfos['sma_info']['size_vars']  = functions::getsize($cache_user['mem_size']);

        return $apc_sysinfos;
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