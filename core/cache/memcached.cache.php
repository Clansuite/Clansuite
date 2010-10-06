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
     * @author     Jens-Andr� Koch <vain@clansuite.com>
     * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 * Clansuite Cache Handler for Memcached
 *
 * memcached is a high-performance, distributed memory object caching system, generic in nature,
 * but intended for use in speeding up dynamic web applications by alleviating database load.
 * memcached was developed by Danga Interactive to enhance the speed of LiveJournal.com.
 * You need two things to get this running: a memcache daemon (server) and the php extension memcached.
 *
 * More information can be obtained here:
 * @link http://www.danga.com/memcached/
 * @link http://php.net/manual/en/book.memcached.php
 *
 * See also the new implementation by Andrei Zmievsk based on libmemcached and memcached.
 * @link http://github.com/andreiz/php-memcached/tree/master
 * @link http://pecl.php.net/package/memcached ()
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Memcached implements Clansuite_Cache_Interface
{
    /**
     * @var object PHP Memcache instance
     */
    public $memcache = null;

    /**
     * @var array Array with one or multiple Memcached Servers.
     */
    private $memcached_servers = array();

    /**
     * Constructor.
     *
     * Instantiate and connect to Memcache Server
     */
    function __construct()
    {
        if(extension_loaded('memcache') === false)
        {
            throw new Exception('The PHP extension memcached (cache) is not loaded! You may enable it in "php.ini"!', 300);
        }

        # instantiate object und set to class
        $this->memcache = new Memcache;
        $config = Clansuite_CMS::getClansuiteConfig();

        # @todo fetch configuration and connection data
        # @todo one server / multiple servers

        /**
         * Memcache Serverpool
         *
         * if memcache server pooling should be used
         * it impossible to use connect/pconnect, but have to addServers
         */
        if($config['cache']['memcached_serverpool'] === true)
        {
            $this->memcache->addServer('servernode1', 11211);
            $this->memcache->addServer('servernode2', 11211);
            $this->memcache->addServer('servernode3', 11211);
        }
        else # no serverpool is used
        {
            # establish (persistent) connection to the one memcache server
            if($config['cache']['memcached_pconnect'] === true)
            {
                # persistent connect
                if ( ! $this->memcache->pconnect($config['cache']['memcached_host'], # 127.0.0.1
                                                 $config['cache']['memcache_port'])  )# 11211
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
    public function __set($key, $value)
    {
        $this->store($key, $value);
    }

    /**
     * Fetches Data by $key from the Memcache
     *
     * @param $key
     */
    public function __get($key)
    {
        return $this->fetch($key);
    }

    /**
     * setServer(s)
     * adds one or multiple Servers
     *
     * @link http://de2.php.net/manual/en/memcached.addserver.php   One Server
     * @link http://de2.php.net/manual/en/memcached.addservers.php  Multiple Servers per Array
     * @param array $servers
     * @return boolean
     */
    public function setServer($servers)
    {
        if(!$servers)
        {
            Clansuite_Exception('The Memcache Server Array empty. No memcache server to add.');
        }

        if(is_array($servers))
        {
            foreach($servers as $server)
            {
                $this->memcache->addservers($servers['host'], $servers['port'], $servers['weight']);
            }
        }
        else
        {
            $this->memcache->addserver($host, $port, $weigth);
        }
    }

    /**
     * getServer
     *
     * @return $this->memcached_servers
     */
    public function  getServers()
    {
        return $this->memcached_servers;
    }

    /**
     * Contains checks if a key exists in the cache
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function contains($key)
    {
        # md5'ify the key
        $key = md5($key);
        if( true === $this->memcache->get($key))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Convenience/shortcut method for fetch
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function get($key)
    {
        $this->fetch($key);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function fetch($key)
    {
        if(!is_array($key))
        {
            $key = (array) $key;
        }

        # memcache keynames have a maximal length restriction of 250 chars
        if(mb_strlen($key) > 250)
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
                $result = (array) $result;
            }
            return $result;
        }
    }

    /**
     * Convenience/shortcut method for storing data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was succesfully cached, false on failure
     */
    public function set($key, $data, $cache_lifetime)
    {
        $this->store($key, $data, $cache_lifetime);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was succesfully cached, false on failure
     */
    public function store($key, $data, $cache_lifetime)
    {
        $compression = $this->config['cache']['memcached_autocompression'];

        if($cache_lifetime === null)
        {
            $cache_lifetime = $this->config['cache']['memcached_lifetime'];
        }

        if(!is_array($data))
        {
            $data = (array) $data;
        }

        # memcache keynames have a maximal length restriction of 250 chars
        if(mb_strlen($key) > 250)
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
    public function delete($keys, $time = null)
    {
        if(!is_array($keys))
        {
            $keys = (array) $keys;
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
    public function delete_all()
    {
        return $this->memcache->flush;
    }

    /**
     * Display Memcached Usage Informations
     */
    public function stats()
    {
        if(extension_loaded('memcache') === false)
        {
            return;
        }

        # get Extended Stats and Version
        $extended_stats = $this->memcache->getExtendedStats();
        $version        = $this->memcache->getVersion();
        # return $this->memcache->memcache_get_version($memcache);

        # combine arrays
        $stats = array_merge_recursive($extended_stats, $version);

        return $stats;
    }

    public function __destruct()
    {
        $this->memcache->close();
    }
}
?>