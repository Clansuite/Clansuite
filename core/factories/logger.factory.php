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
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: httpresponse.core.php 2614 2008-12-05 21:18:45Z vain $response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

class Clansuite_Logger_Factory
{
    /**
     * getLogger
     *
     * @param $logger_type String (A Logger Name like "file", "db")
     * @param $injector Dependency Injector Phemto
     * @static
     * @access public
     * @return Renderer Object
     */
    public static function getLogger($logger_type, Phemto $injector)
    {
        try
        {
			$file = ROOT_CORE .'logger'.DS. strtolower($logger_type) .'.logger.php';
        	if (is_file($file) != 0)
			{
				require_once($file);
	            $class = 'logger_'. $logger_type;
	            if (class_exists($class))
	            {
	                # instantiate and return the logger and pass $injector into
	                $logger = new $class($injector);
	                # var_dump($logger);
	                return $logger;
	            }
	            else
	            {
	            	 throw new LoggerFactoryClassNotFoundException($class);
	            }
	        }
			else
			{
				throw new LoggerFactoryFileNotFoundException($file);
	        }
	    }
		catch(Exception $e) {}
    }
}

/**
 * Clansuit Exception - LoggerFactoryClassNotFoundException
 *
 * @package clansuite
 * @category    core
 * @subpackage exceptions
 */
class LoggerFactoryClassNotFoundException extends Exception
{
	function __construct($class)
	{
		parent::__construct();
	  	echo 'Logger_Factory -> Class not found: ' . $class;
	  	die();
	}
}

/**
 * Clansuit Exception - LoggerFactoryFileNotFoundException
 *
 * @package clansuite
 * @category    core
 * @subpackage exceptions
 */
class LoggerFactoryFileNotFoundException extends Exception
{
	function __construct($file)
	{
		parent::__construct();
		echo 'Logger_Factory -> File not found: ' . $file;
		die();
	}
}

/**
 * Clansuite_Logger_Interface
 * 
 * Purpose: All Loggers must implement the following functions.
 */
class Clansuite_Logger_Interface()
{
    function getInstance($injector);
    function writeLog($string);
}
?>