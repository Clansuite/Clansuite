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
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Interface for all Cachehandler Classes to implement
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
interface Clansuite_Cache_Interface
{
    function contains($key);
    function fetch($key);
    function store($key, $data, $cache_lifetime);
    function delete($key);
    function stats();
}

/**
 * Cache Factory
 *
 * The static method getCache() returns the included and instantiated Cache Engine Object!
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Factory
{
    /**
     * Returns the instance of the requested cache_adapter
     *
     * @param string $adapter A Cache Engine Name like "apc", "xcache", "memcache" or "file"
     * @return object Cache_Engine
     */
    public static function getCache($adapter)
    {
        $file = ROOT_CORE . '/cache/' . mb_strtolower($adapter) . '.cache.php';

        if(is_file($file) === true)
        {
            $class = 'Clansuite_Cache_' . $adapter;

            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(class_exists($class, false))
            {
                $cache = new $class();
                return $cache;
            }
            else
            {
                throw new Clansuite_Exception('Cache_Factory -> Class not found: ' . $class, 30);
            }
        }
        else
        {
            throw new Clansuite_Exception('Cache_Factory -> File not found: ' . $file, 31);
        }
    }
}
?>