<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Cache;

class Cache
{
    /**
     * The cache object wrapping the cache access.
     *
     * @var Koch_Cache $cacheObject The cache object.
     */
    private static $cacheObject = null;

    /**
     * Instantiates a cache adapter
     *
     * @param string $adapter The cache adapter to instantiate. Defaults to apc.
     * @return Koch_Cache_Interface Cache object of the requested adapter type.
     */
    public static function instantiate($adapter = 'apc')
    {
        if(self::$cacheObject === null)
        {
            self::$cacheObject = Koch_Cache_Factory::getCache($adapter);
        }

        return self::$cacheObject;
    }

    /**
     * Checks, if data for a key is stored in the cache.
     *
     * @param string $key
     * @return bool True, the key/data exists.
     */
    public static function contains($key)
    {
        return self::$cacheObject->contains($key);
    }

    /**
     * Retrieves data by key from the cache.
     *
     * @param type $key
     * @return mixed|null Returns data or null.
     */
    public static function fetch($key = null)
    {
        $data = self::$cacheObject->fetch($key);

        if(!$data)
        {
            return null;
        }

        return $data;
    }

    /**
     * Stores data by key to the cache.
     *
     * @param type $key The key to retrieve the data form the cache.
     * @param type $data The data to store in the cache.
     * @param int $cache_lifetime Cache lifetime in minutes.
     * @return bool
     */
    public static function store($key, $data, $cache_lifetime = 10)
    {
        return self::$cacheObject->set($key, $data, $cache_lifetime);
    }

    /**
     * Deletes data by key from the cache.
     *
     * @param string $key
     * @return bool
     */
    public static function delete($key)
    {
        return self::$cacheObject->delete($key);
    }

    /**
     * Clears the cache completely.
     *
     * @return bool
     */
    public static function clear()
    {
        return self::$cacheObject->clear();
    }

    /**
     * Retrieves an object from the cache.
     *
     * @return object
     */
    public static function fetchObject($key = null)
    {
        $object = self::$cacheObject->get($key);

        if(is_string($object))
        {
            return unserialize($object);
        }

        return $object;
    }

    /**
     * Stores an object in the cache.
     *
     * @param type $key The key for retrieving the object.
     * @param type $object The object to store the cache.
     * @param type $cache_lifetime Cache liftime in minutes.
     * @return boolean True in caching success. False on caching failure.
     */
    public static function storeObject($key, $object, $cache_lifetime = 10)
    {
        return self::$cacheObject->set($key, serialize($object), $cache_lifetime);
    }
}
?>