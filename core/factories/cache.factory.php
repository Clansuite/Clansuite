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

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.'); }

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
 * The static method getCache() returns the included and instantiated
 * Cache Engine Object!
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class Clansuite_Cache_Factory
{
    /**
     * getCache
     *
     * @param $cache_type String (A Cache Engine Name like "apc", "xcache", "memcache" or "file")
     * @param $injector Dependency Injector Phemto
     * @return Cache Engine Object
     */
    public static function getCache($cache_type, Phemto $injector)
    {
        try
        {
			$file = ROOT_CORE .'/cache/'. strtolower($cache_type) .'.cache.php';
        	if (is_file($file) != 0)
			{
                $class = 'Clansuite_Cache_'. $cache_type;
				if( !class_exists($class,false) ) { require($file); }

	            if (class_exists($class,false))
	            {
	                # instantiate and return the renderer and pass $injector into
	                $cache = new $class($injector);
	                # var_dump($Cache);
	                return $cache;
	            }
	            else
	            {
	            	 throw new CacheFactoryClassNotFoundException($class);
	            }
	        }
			else
			{
				throw new CacheFactoryFileNotFoundException($file);
	        }
	    }
		catch(Exception $e) {}
    }
}

/**
 * Clansuit Exception - CacheFactoryClassNotFoundException
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class CacheFactoryClassNotFoundException extends Exception
{
	function __construct($class)
	{
		parent::__construct();
	  	echo 'Cache_Factory -> Class not found: ' . $class;
	  	die();
	}
}

/**
 * Clansuit Exception - CacheFactoryFileNotFoundException
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Cache
 */
class CacheFactoryFileNotFoundException extends Exception
{
	function __construct($file)
	{
		parent::__construct();
		echo 'Cache_Factory -> File not found: ' . $file;
		die();
	}
}
?>