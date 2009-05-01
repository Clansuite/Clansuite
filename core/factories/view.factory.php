<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: view_factory.class.php 2584 2008-11-21 22:39:52Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * View Factory
 *
 * The static method getRenderer() returns the included and instantiated
 * Rendering Engine Object - which is the View in MVC!
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
class view_factory
{
    /**
     * getRenderer
     *
     * @param $view_type String (A Renderer Name like "smarty", "phptal", "native")
     * @param $injector Dependency Injector Phemto
     * @return Renderer Object
     */
    public static function getRenderer($view_type, Phemto $injector)
    {
        try
        {
			$file = ROOT_CORE .'views'.DS. strtolower($view_type) .'.view.php';
        	if (is_file($file) != 0)
			{
				require_once($file);
	            $class = 'view_'. $view_type;
	            if (class_exists($class))
	            {
	                # instantiate and return the renderer and pass $injector into
	                $view = new $class($injector, $injector->create('Clansuite_Config'));
	                #var_dump($view);
	                return $view;
	            }
	            else
	            {
	            	 throw new ViewFactoryClassNotFoundException($class);
	            }
	        }
			else
			{
				throw new ViewFactoryFileNotFoundException($file);
	        }
	    }
		catch(Clansuite_Exception $e) {}
    }
}

/**
 * Clansuit Exception - ViewFactoryClassNotFoundException
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
class ViewFactoryClassNotFoundException extends Exception
{
	function __construct($class)
	{
		parent::__construct();
	  	echo 'View_Factory -> Class not found: ' . $class;
	  	die();
	}
}

/**
 * Clansuit Exception - ViewFactoryFileNotFoundException
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  View
 */
class ViewFactoryFileNotFoundException extends Exception
{
	function __construct($file)
	{
		parent::__construct();
		echo 'View_Factory -> File not found: ' . $file;
		die();
	}
}
?>