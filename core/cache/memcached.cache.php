<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-2008)
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
    public $memcache = null;

    /**
     * Instantiate and connect to Memcache Server
     */
    function __construct()
    {
        # instantiate object und set to class
        $this->memcache = new Memcache;

        # fetch configuration and connection data
        $config = clansuite_registry::getConfigurationStatic();

        # if memcache server pooling should be used
        # we can't use connect/pconnect, but have to addServers
        if($config['cache']['memcached_serverpool'] === true)
        {
            $this->memcache->addServer('servernode1', 11211);
            $this->memcache->addServer('servernode1', 11211);
            $this->memcache->addServer('servernode1', 11211);
        }
        else # no serverpool is used
        {
            # establish (persistent) connection to the one memcache server
            if($config['cache']['memcached_pconnect'] === true)
            {
                # persistent connect
                if ( ! $this->memcache->pconnect($config['cache']['memcached_host'], # 127.0.0.1
                                                 $config['cache']['memcache_port']) # 11211 )
                {
                    throw new Clansuite_Exception('Persistant Connect to Memcache Server failed.');
                }
            }
            else
            {
                # normal connect
                if ( ! $this->memcache->connect($config['cache']['memcached_host'],
                                                $config['cache']['memcache_port']))
                {
                    throw new Clansuite_Exception('Connect to Memcache Server failed');
                }

            }
        }

        # Set Compression
        if($config['cache']['memcached_autocompression'] === true)
        {
            # compressionsize  = automatic compression on values larger than compression-size in bytes, e.g. 20000 bytes
            # compressionratio = 0 = 0%; 0.5 = 50%; 1 = 100%
            $this->memcache->setCompressThreshold($config['cache']['memcached_compressionsize'],
                                                  $config['cache']['memcached_compressionratio']);
        }

    }

    /**
     * Writes Data by $key $value pair into the cache
     *
     * @param $key
     * @param $value
     */
    function __set($key, $value)
    {
        $this->store($key, $value);
    }

    /**
     * Fetches Data by $key from the Memcache
     *
     * @param $key
     */
    function __get($key)
    {
        return $this->fetch($key);
    }

    // apc_fetch
    function fetch($key)
    {
        if(!is_array($key))
        {
            $key = (array)$key;
        }

        # memcache keynames have a maximal length restriction of 250 chars
        if(strlen($key) > 250)
        {
            $key = md5($key); # md5 = 32 chars
        }

        $result = array();
        $result = $this->memcache->get($key);

        if($result === false)
        {
            return false;
        }
        else
        {
            if(!is_array($result))
            {
                $result = (array)$result;
            }
            return $result;
        }
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
        $compression = $this->config['cache']['memcached_autocompression'];

        if(is_null($cache_lifetime))
        {
            $cache_lifetime = $this->config['cache']['memcached_lifetime'];
        }

        if(!is_array($data))
        {
            $data = (array)$data;
        }

        # memcache keynames have a maximal length restriction of 250 chars
        if(strlen($key) > 250)
        {
            $key = md5($key); # md5 = 32 chars
        }

        if( $this->memcache->set($key, $data, $compression, $cache_lifetime) === true )
        {
            return true;
        }
        return false;
    }

    /**
     * Deletes a $key or an array of $keys from the Memcache
     *
     * @param $key string or array
     * @param $time delaytime before deletion
     */
    function delete($keys, $time = null)
    {
        if(!is_array($keys))
        {
            $keys = (array)$keys;
        }

        $time = (int) $time; // delete delayed

        foreach($keys as $key)
        {
            return $this->memcache->delete($key, $time);
        }
    }

    /**
     * Delete_all flushes the Cache
     * Memcache::flush() doesn't actually free any resources,
     * it only marks all the items as expired, so occupied memory will be overwritten by new items.
     *
     * @return a flushed cache
     */
    function delete_all()
    {
        return $this->memcache->flush;
    }

    /**
     * Display Memcached Usage Informations
     */
    function stats()
    {
        # return $this->memcache->memcache_get_version($memcache);
        # return $this->memcache->getExtendedStats();
    }

    public function __destruct()
    {
        $this->memcache->close();
    }
}
?>