<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf
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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}
    
/**
 * View Factory 
 * includes and instantiates the Renderer Object
 */
class view_factory
{
    /**
     * getRenderer
     *
     * @params $view_type, $view,  $injector
     * @access public
     * @return Renderer Object
     */
    public static function getRenderer($view_type, $injector)
    {        
        try
        {	
			$file = ROOT_CORE .'/views/view_'. strtolower($view_type) .'.class.php';
        	if (is_file($file) != 0)
			{	
				require_once($file);
	            $class = 'view_'. $view_type;
	            if (class_exists($class))
	            {
	                //instantiate and return the renderer and pass $injector into
	                $view = new $class($injector);
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
		catch(Exception $e) {}
    }
}

class ViewFactoryClassNotFoundException extends Exception 
{
	function __construct($class)
	{
		parent::__construct();
	  	echo 'View_Factory -> Class not found: ' . $class;
	  	die();
	}
}

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