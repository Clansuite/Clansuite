<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf ? 2005-2007
    * http://www.clansuite.com/
    *
    * File:         httprequest.class.php
    * Requires:     PHP 5.2
    *
    * Purpose:      Clansuite Core Class for Request Handling
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
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
 * Abstraction for $_REQUEST access with methods
 */
interface RequestInterface
{
    public function getParameterNames();
    public function issetParameter($name);
    public function getParameter($name);
    public function getHeader($name);

    #public function getAuthData();
    public function getRemoteAddress();
}

/**
 * Request class for encapsulating access to superglobal $_REQUEST
 * two ways of access: via methods and via arrayaccess array handling
 * 
 * @todo: split $_REQUEST into GET and POST with each seperate access methods
 *
 */
class httprequest implements RequestInterface, ArrayAccess
{
    private $parameters;

    /**
     * Import SUPERGLOBAL $_REQUEST into $parameters
     */
    public function __construct()
    {
        $this->parameters = $_REQUEST;
    }

    /**
     * List of all parameters in the REQUEST
     */
    public function getParameterNames()
    {
        return array_keys($this->parameters);
    }

    /**
     * isset, checks if a certain parameter exists in the parameters array
     *
     * @todo: docblock
     * @param
     */
    public function issetParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * get, returns a certain parameter if existing
     *
     * @todo: docblock
     * @param
     */
    public function getParameter($name)
    {
        if (isset($this->parameters[$name]))
        {
            return $this->parameters[$name];
        }
    }

    /**
     * Get Value of a specific http-header
     * 
     * @todo: docblock
     * @param
     */
    public function getHeader($name)
    {
        $name = 'HTTP_' . strtoupper(str_replace('-','_', $name));
        if (isset($_SERVER[$name]))
        {
            return $_SERVER[$name];
        }
        return null;
    }

    /**
     * Get $_SERVER REMOTE_ADDRESS
     */
    public function getRemoteAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Implementation of SPL ArrayAccess
     * only offsetExists and offsetGet are relevant
     */
    public function offsetExists($offset)
    {
        return isset($this->parameters[$offset]);        
    }

    public function offsetGet($offset)
    {
        return $this->getParameter($offset);
    }

    // not setting request vars
    public function offsetSet($offset, $value) {}

    // not unsetting request vars
    public function offsetUnset($offset)
    {
        //unset($this->parameter[$offset]);
        //return true;
    }
}
?>