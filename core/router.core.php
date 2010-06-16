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
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Router
 *
 * Clansuite_Router does URL Formatting and internal Rewriting.
 * The URL is segmented and restructured to fit the internal route to a controller.
 * The internal routes are described in a central routing configuration file.
 * This central config is updated on installation and deinstallation of modules and plugins.
 * @see Clansuite_Routes_Manager
 *
 * Normally all requests made map to a specific physical resource rather than a logical name.
 * With Routing you are able to map a logical name to a specific physical name.
 * Example: map a logical URL (a mod_rewritten one) to a Controller/Method/Parameter
 * Map a FileRequest via logical URL (a mod_rewritten one) to a DownloadController/Method/Parameter
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * SPL Iterator and ArrayAccess are used for fast iteration and easier access to the stored routes.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
class Clansuite_Router implements Iterator, ArrayAccess, Clansuite_Router_Interface
{
    public function addRoute($name, array $route)
    {

    }

    public function addRoutes(array $routes)
    {

    }

    public function getRoutes()
    {

    }

    public function delRoute($name)
    {

    }

    public function generateURL($action, $args = null, $params = null, $fragment = null)
    {

    }

    public function route()
    {

    }

    /**
     * Implementation of SPL Iterator
     */

    /**
     * Rewind is ONLY called at the start of the Iteration.
     * Sets the iterator to the first element on the $routes array.
     */
    public function rewind()
    {
        return reset($this->routes);
    }

    /**
     * Ensures a valid element exists after a call to rewind() and next().
     */
    public function valid()
    {

    }

    /**
     * @return array Returns the next array element of $routes.
     */
    public function next()
    {
        return next($this->routes);
    }

    /**
     * @return array Returns the current array element value of $routes.
     */
    public function current()
    {
        return current($this->routes);
    }

    /**
     * @return array Returns the key of the current element of $routes..
     */
    public function key()
    {
        return key($this->routes);
    }

    /**
     * Implementation of SPL ArrayAccess
     */

    /**
     * Instead of working with $router->addRoute(name,map);
     * you may now access the routing table as an array $router[$route] = $map;
     */
    final public function offsetSet($route, $target)
    {
        $this->addRoute($route, $target);
    }

    final public function offsetGet($name)
    {
        if(array_key_exists($name, $this->routes) === true)
        {
            return $this->routes[$name];
        }
        else
        {
            return NULL;
        }
    }

    final public function offsetExists($name)
    {
        return array_key_exists($name, $this->routes);
    }

    final public function offsetUnset($name)
    {
        unset($this->routes[$name]);
    }
}

/**
 * Interface for Clansuite_Router(s)
 *
 * A router has to implement the following methods to resolve the Request to a Module and the Action/Command.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
interface Clansuite_Router_Interface
{
    /**
     * Getter and Setter for $routes
     */
    function addRoute($name, array $route);
    function addRoutes(array $routes);
    function getRoutes();
    function delRoute($name);

    /**
     * CALL/MAP -> URL
     */
    function generateURL($action, $args = null, $params = null, $fragment = null);

    /**
     * URI -> MAP MATCHING -> CALL
     */
    function route();
}
?>