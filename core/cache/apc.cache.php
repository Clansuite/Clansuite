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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Cache Handler for APC (Alternative PHP Cache)
 *
 * APC is a) an opcache and b) a memory based cache.
 *
 * @link http://de3.php.net/manual/de/ref.apc.php
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_APC implements Clansuite_Cache_Interface
{
    public function __construct()
    {
        if(extension_loaded('apc') === false);
        {
            throw new Clansuite_Exception('The PHP extension APC (Alternative PHP Cache) is not loaded. You may enable it in "php.ini"!', 300);
        }
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the data was successfully fetched from the cache, false on failure
     */
    public function fetch($key)
    {
        return apc_fetch($key);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function store($key, $data, $cache_lifetime, $overwrite = false)
    {
        if($key === null)
        {
            return false;
        }
        elseif($overwrite == false)
        {
            return apc_add($key, $data, $cache_lifetime);
        }
        else # overwrite
        {
            return apc_store($key, $data, $cache_lifetime);
        }
    }

    /**
     * Delete data by key from cache
     *
     * @param string $key Identifier for the data
     * @return int Number of keys deleted.
     */
    public function delete($key)
    {
        $key = (array) $key;
        $keys_deleted = 0;

        foreach($key as $cacheKey)
        {
            return if(true === apc_delete($key))
            {
                $keys_deleted++;
            }
        }

        return $keys_deleted;
    }

    public function clear()
    {
        return apc_clear_cache();
    }

    /**
     * Contains checks if a key exists in the cache
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function contains($key)
    {
        if(true === apc_fetch($key))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get stats and usage Informations for display from APC
     * 1. Shared Memory Allocation
     * 2. Cache Infos / Meta-Data
     */
    public function stats()
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
        $apc_sysinfos['sma_info']['mem_size'] = $apc_sysinfos['sma_info']['num_seg'] * $apc_sysinfos['sma_info']['seg_size'];

        # Calculate "APC Memory Usage" ( mem_size - avail_mem )
        $apc_sysinfos['sma_info']['mem_used'] = $apc_sysinfos['sma_info']['mem_size'] - $apc_sysinfos['sma_info']['avail_mem'];

        # Calculate "APC Free Memory Percentage" ( mem_size*100/mem_used )
        $apc_sysinfos['sma_info']['mem_avail_percentage'] = sprintf('(%.1f%%)', $apc_sysinfos['sma_info']['avail_mem'] * 100 / $apc_sysinfos['sma_info']['mem_size']);

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

         # div by zero fix
        if($total_hits == 0)
        {
            $total_hits = 1;
        }

        $hit_rate = $apc_sysinfos['system_cache_info']['num_hits'] * 100 / $total_hits;
        $apc_sysinfos['system_cache_info']['hit_rate_percentage'] = sprintf('(%.1f%%)', $hit_rate);

        # Calculate "APC Miss Rate Percentage"
        $apc_sysinfos['system_cache_info']['miss_rate_percentage'] = sprintf('(%.1f%%)', $apc_sysinfos['system_cache_info']['num_misses'] * 100 / $total_hits);
        $apc_sysinfos['system_cache_info']['files_cached'] = count($apc_sysinfos['system_cache_info']['cache_list']);
        $apc_sysinfos['system_cache_info']['files_deleted'] = count($apc_sysinfos['system_cache_info']['deleted_list']);

        $time = time();
        # Request Rate (hits, misses) / cache requests/second
        $apc_sysinfos['system_cache_info']['req_rate'] = sprintf('%.2f', ($apc_sysinfos['system_cache_info']['num_hits'] + $apc_sysinfos['system_cache_info']['num_misses']) / ($time - $apc_sysinfos['system_cache_info']['start_time']));
        $apc_sysinfos['system_cache_info']['hit_rate'] = sprintf('%.2f', ($apc_sysinfos['system_cache_info']['num_hits']) / ($time - $apc_sysinfos['system_cache_info']['start_time']));
        $apc_sysinfos['system_cache_info']['miss_rate'] = sprintf('%.2f', ($apc_sysinfos['system_cache_info']['num_misses']) / ($time - $apc_sysinfos['system_cache_info']['start_time']));
        $apc_sysinfos['system_cache_info']['insert_rate'] = sprintf('%.2f', ($apc_sysinfos['system_cache_info']['num_inserts']) / ($time - $apc_sysinfos['system_cache_info']['start_time']));
        # size
        $apc_sysinfos['system_cache_info']['size_files'] = Clansuite_Functions::getsize($apc_sysinfos['system_cache_info']['mem_size']);

        $apc_sysinfos['settings'] = ini_get_all('apc');

        /**
         * ini_get_all array mod: for each accessvalue
         * add the name of the PHP ACCESS CONSTANTS as 'accessname'
         * @todo: cleanup?
         */
        foreach($apc_sysinfos['settings'] as $key => $value)
        {
            foreach($value as $key2 => $value2)
            {
                if($key2 == 'access')
                {
                    $name = '';

                    # accessvalue => constantname
                    if($value2 == '1')
                    {
                        $name = 'PHP_INI_USER';
                    }
                    if($value2 == '2')
                    {
                        $name = 'PHP_INI_PERDIR';
                    }
                    if($value2 == '4')
                    {
                        $name = 'PHP_INI_SYSTEM';
                    }
                    if($value2 == '7')
                    {
                        $name = 'PHP_INI_ALL';
                    }

                    # add accessname to the original array
                    $apc_sysinfos['settings'][$key]['accessname'] = $name;
                    unset($name);
                }
            }
        }

        #$apc_sysinfos['sma_info']['size_vars']  = Clansuite_Functions::getsize($cache_user['mem_size']);

        return $apc_sysinfos;
    }
}
?>