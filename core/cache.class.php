<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
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
    * @copyright  Jens-Andre Koch (2005-2007)
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
 */
interface ClansuiteCacheInterface
{
        function fetch($key);
        function store($key, $data, $time_to_live);
        function delete($key);
}

/**
 * Cache Handler
 *
 */
class cache
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
}

/**
 * APC = Alternative PHP Cache
 */
class clansuite_cache_apc implements ClansuiteCacheInterface
{
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
 * memcached
 */
class clansuite_cache_memcached implements ClansuiteCacheInterface
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