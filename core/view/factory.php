<?php
   /**
    * Koch Framework
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\View;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Renderer Factory
 *
 * The static method getRenderer() returns the included and instantiated
 * Rendering Engine Object - which is the View in MVC!
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Renderer
 */
class Factory
{
    /**
     * getRenderer
     *
     * @param $adapter String (A Renderer Name like "smarty", "phptal", "native")
     * @return Renderer Object
     */
    public static function getRenderer($adapter, $injector)
    {
        $file = KOCH . 'view/renderer/' . strtolower($adapter) . '.php';

        if(is_file($file) === true)
        {
            $class = 'Koch\View\Renderer\\' . $adapter;

            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(true === class_exists($class, false))
            {
                # instantiate and return the renderer and pass Config and Response objects to it
                $view = new $class($injector->instantiate('Koch\Config\Config'));
                return $view;
            }
            else
            {
                throw new \Exception('Renderer_Factory -> Class not found: ' . $class, 61);
            }
        }
        else
        {
            throw new \Exception('Renderer_Factory -> File not found: ' . $file, 61);
        }
    }
}
?>