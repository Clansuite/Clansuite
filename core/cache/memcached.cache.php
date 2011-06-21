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
 * Clansuite Cache Handler for Memcached
 *
 * memcached is a high-performance, distributed memory object caching system, generic in nature,
 * but intended for use in speeding up dynamic web applications by alleviating database load.
 * memcached was developed by Danga Interactive to enhance the speed of LiveJournal.com.
 * You need two things to get this running: a memcache daemon (server) and the php extension memcached.
 *
 * More information can be obtained here:
 * @link http://www.danga.com/memcached/
 * @link http://libmemcached.org/libMemcached.html
 * @link http://php.net/manual/en/book.memcached.php
 *
 * See also the new implementation by Andrei Zmievsk based on libmemcached and memcached.
 * @link http://github.com/andreiz/php-memcached/tree/master
 * @link http://pecl.php.net/package/memcached
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Memcached implements Clansuite_Cache_Interface
{
    /**
     * Memcached Server
     */
    const SERVER_HOST = '127.0.0.1';
    const SERVER_PORT =  11211;
    const SERVER_WEIGHT  = 1;

    /**
     * @var object PHP Memcached instance
     */
    protected $memcached = null;

    /**
     * Constructor.
     *
     * Instantiate and connect to Memcache Server
     */
    function __construct()
    {
        if(extension_loaded('memcached') === false)
        {
            throw new Exception('The PHP extension memcache (cache) is not loaded! You may enable it in "php.ini"!', 300);
        }

        # instantiate object und set to class
        $this->memcached = new \Memcached;
        $this->memcached->addServer(SERVER_HOST, SERVER_PORT, SERVER_WEIGTH);

        $this->memcached->setOption(Memcached::OPT_COMPRESSION, true);
        # LIBKETAMA compatibility will implicitly declare the following two things:
        #$this->memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
        #$this->memcached->setOption(Memcached::OPT_HASH, Memcached::MD5);
        $this->memcached->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
    }
    
    /**
     * Contains checks if a key exists in the cache
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    public function contains($key)
    {
        if( true === $this->memcached->get($key))
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
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function get($key)
    {
        return $this->fetch($key);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    public function fetch($key)
    {
        $result = $this->memcached->get($key);

        if($result === false)
        {
            return false;
        }
        else
        {
            # typecast $key to array
            if(is_array($result) === false)
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
     * @return boolean True if the data was successfully cached, false on failure
     */
    public function set($key, $data, $cache_lifetime = 0)
    {
        return $this->store($key, $data, $cache_lifetime);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure.
     */
    public function store($key, $data, $cache_lifetime = 0)
    {
        # typecast $data to array
        if(is_array($data) === false)
        {
            $data = (array) $data;
        }

        if( $this->memcached->set($key, $data, $cache_lifetime) === true )
        {
            return true;
        }
        return false;
    }

    /**
     * Deletes a $key or an array of $keys from the Memcache
     *
     * @param $key string or array
     */
    public function delete($keys)
    {
        # typecast $keys to array
        if(is_array($keys) === false)
        {
            $keys = (array) $keys;
        }

        foreach($keys as $key)
        {
            return $this->memcached->delete($key);
        }
    }

    /**
     * Delete_all flushes the Cache
     *
     * @return a flushed cache
     */
    public function delete_all()
    {
        return $this->memcached->flush();
    }

    /**
     * Display Memcached Usage Informations
     */
    public function stats()
    {
        $version    = $this->memcached->getversion();
        $stats      = $this->memcached->getstats();
        $serverlist = $this->memcached->getserverlist();

        # combine arrays
        return compact($version, $stats, $serverlist);
    }
    
    /**
     * Returns an the Memcached instance
     * 
     * @return object \Memcached Cache Engine
     */
    public function getEngine()
    {
        return $this->memcached;
    }

    /**
     * The connection, which was opened using Memcache::connect()
     * will be automatically closed at the end of script execution.
     * We are nice and close it on object destruction.
     */
    public function __destruct()
    {
        if($this->memcached !== null)
        {
            $this->memcached->close();
        }
    }
}
?>