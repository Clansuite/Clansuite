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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Renderer Factory
 *
 * The static method getRenderer() returns the included and instantiated
 * Rendering Engine Object - which is the View in MVC!
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */
class Clansuite_Renderer_Factory
{

    /**
     * getRenderer
     *
     * @param $adapter String (A Renderer Name like "smarty", "phptal", "native")
     * @return Renderer Object
     */
    public static function getRenderer($adapter, $injector)
    {
        $file = ROOT_CORE . 'renderer' . DS . mb_strtolower($adapter) . '.renderer.php';

        if(is_file($file) === true)
        {
            $class = 'Clansuite_Renderer_' . $adapter;
            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(class_exists($class, false))
            {
                # instantiate and return the renderer and pass Config and Response objects to it
                $view = new $class($injector->instantiate('Clansuite_Config'),
                                $injector->instantiate('Clansuite_HttpResponse'));
                return $view;
            }
            else
            {
                throw new Clansuite_Exception('Renderer_Factory -> Class not found: ' . $class, 61);
            }
        }
        else
        {
            throw new Clansuite_Exception('Renderer_Factory -> File not found: ' . $class, 61);
        }
    }
}
?>