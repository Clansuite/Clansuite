<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
    * http://www.clansuite.com/
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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

/**
 * Interfaces
 * for all Cache Classes to implement
 *
 * @package clansuite
 * @subpackage session
 * @category interfaces
 */
 */
interface Clansuite_CacheInterface
{
        function fetch($key);
        function store($key, $data, $time_to_live);
        function delete($key);
}

/**
 * Clansuite Cache Handler for APC (Alternative PHP Cache)
 *
 * 
 *
 * @package clansuite
 * @subpackage cache
 * @category caches
 */
class Clansuite_Cache_APC implements Clansuite_CacheInterface
{
    function __construct()
    {
        // Check if APC extension is loaded
        if( !defined('GID_EXTENSION_LOADED_APC') )
            define( 'GID_EXTENSION_LOADED_APC', extension_loaded('apc') );

        // only get the data off the text file if we can't get it from
        // APC i.e. from memory
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
    function store($key,$data,$time_to_live)
    {
        return apc_store($key,$data,$time_to_live);
    }

    // apc_delete
    function delete($key)
    {
        return apc_delete($key);
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
class Clansuite_Cache_Memcached implements Clansuite_CacheInterface
{
    $cache = NULL;

    function __construct()
    {
        // Objekt instanzieren und intern zuweisen
        $this->cache = new Memcache;

        // Verbindung zum memcached Server herstellen
        $config = clansuite_registry::getConfigurationStatic();
        $this->cache->connect($config['cache']['memcached_host'], $config['cache']['memcache_port']);
    }

    // Daten in den Cache schreiben
    function __set($key, $value)
    {
        $this->cache->set($key, $value);
    }

    // Daten aus dem Cache lesen
    function __get($key)
    {
        return $cache->get($key);
    }

    // memcache_delete
    function delete($key)
    {
        return $this->cache->delete($key);
    }

    // memcache_delete_all
    function delete_all($key)
    {
        return $this->cache->flush;
    }

    function info()
    {
        // Momentanen Speicherverbrauch in Bytes ausgeben
        $memcache->getStats()['bytes'];
    }
}
?>