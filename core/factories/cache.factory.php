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
 * Interface for all Cachehandler Classes to implement
 *
 * @package clansuite
 * @subpackage session
 * @category interfaces
 */
interface Clansuite_Cache_Interface
{
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
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 *
 * @package     clansuite
 * @category    Cache
 * @subpackage  Cache
 */
class cache_factory
{
    /**
     * getCache
     *
     * @param $cache_type String (A Cache Engine Name like "apc", "xcache", "memcache" or "file")
     * @param $injector Dependency Injector Phemto
     * @static
     * @access public
     * @return Cache Engine Object
     */
    public static function getCache($cache_type, Phemto $injector)
    {
        try
        {
			$file = ROOT_CORE .'/cache/'. strtolower($cache_type) .'.class.php';
        	if (is_file($file) != 0)
			{
				require_once($file);
	            $class = 'Clansuite_Cache_'. $cache_type;
	            if (class_exists($class))
	            {
	                # instantiate and return the renderer and pass $injector into
	                $Cache = new $class($injector);
	                # var_dump($Cache);
	                return $Cache;
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
 * @package clansuite
 * @category    core
 * @subpackage exceptions
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
 * @package clansuite
 * @category    core
 * @subpackage exceptions
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