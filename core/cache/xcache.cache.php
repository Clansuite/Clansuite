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
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite Cache Handler for Xcache
 *
 * XCache is a open-source opcode cacher, which means that it accelerates the performance of PHP on servers.
 * It optimizes performance by removing the compilation time of PHP scripts by caching the compiled state of
 * PHP scripts into the shm (RAM) and uses the compiled version straight from the RAM. This will increase
 * the rate of page generation time by up to 5 times as it also optimizes many other aspects of php scripts
 * and reduce serverload.
 * The XCache project is lead by mOo who is also a developer of the Lighttpd, the Webserver also known as lighty.
 *
 * @link http://xcache.lighttpd.net/
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Xcache implements Clansuite_Cache_Interface
{
    /**
     * isCached checks the cache for a key
     *
     * @param string $key Identifier for the data
     * @return boolean true|false
     */
    function isCached($key)
    {
       return xcache_isset($key);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed boolean FALSE if the data was not fetched from the cache, DATA on success
     */
    function fetch($key)
    {
        return xcache_get($key);
    }

    /**
     * Stores data by key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param integer $cache_lifetime How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     */
    function store($key, $data, $cache_lifetime)
    {
         return xcache_set($key, $data, $cache_lifetime);
    }

    /**
     * Delete data by key from cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the data was successfully removed, false on failure
     */
    function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * Get stats and usage Informations for display
     *
     * Seems the XCache API does not provide infos. Are there any cache infos available?
     * @link http://xcache.lighttpd.net/wiki/XcacheApi
     * @todo implement statistics for xcache usage
     */
    function stats()
    {
    }
}
?>