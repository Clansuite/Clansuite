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
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Interface for the Request Object
 *
 * @package clansuite
 * @subpackage core
 * @category interfaces
 */
interface Clansuite_Request_Interface
{
    public function getParameterNames();
    public function issetParameter($name);
    public function getParameter($name);
    public function getHeader($name);

    public function getRequestMethod();
    public function getCookie($name);
    public function isSecure();

    #public function getAuthData();
    public function getRemoteAddress();
}

/**
 * HttpRequest
 *
 * Purpose:  Clansuite Core Class for Request Handling
 *
 * Request class for encapsulating access to the superglobal $_REQUEST.
 * There are two ways of access:
 * (1) via methods and (2) via spl arrayaccess array handling.
 *
 * @todo split $_REQUEST into GET and POST with each seperate access methods
 *
 * @package clansuite
 * @subpackage core
 * @category httprequest
 */
class Clansuite_HttpRequest implements Clansuite_Request_Interface, ArrayAccess
{
    # contains the cleaned $_REQUEST Parameters
    private $request_parameters;

    # contains the cleaned $_POST Parameters
    private $post_parameters;

    # contains the cleaned $_GET Parameters
    private $get_parameters;

    # contains the cleaned $_COOKIE Parameters
    private $cookie_parameters;

    /**
     * Construct the Request Object
     *
     * 1) Filter Globals and Request
     * 2) Run $_REQUEST through Filterset of Intrusion Detection System
     * 3) Import SUPERGLOBAL $_REQUEST into $parameters
     */
    public function __construct()
    {
        # 1) Filter Globals and Request

        // Reverse the effect of register_globals
        if ((bool)ini_get('register_globals') && strtolower(ini_get('register_globals')) != 'off')
        {
            $this->cleanGlobals();
        }

        // if sybase style quoting isn't specified, use ini setting
        if ( !isset($sybase) )
        {
            $sybase = ini_get('magic_quotes_sybase');
        }

        // disable magic_quotes_sybase
        if ( 1 == $sybase )
        {
            ini_set('magic_quotes_sybase', 0);
        }

        // disable magic_quotes_runtime
        set_magic_quotes_runtime(0);

        if ( 1 == get_magic_quotes_gpc() )
        {
            # @todo: Evaluate if a php.ini "magic_quotes off" should be requested from user!
            $this->fix_magic_quotes();
            ini_set('magic_quotes_gpc', 0);
        }

        /**
         *  2) Security
         */

        # a) run IDS
        $doorKeeper = new Clansuite_DoorKeeper;
        $doorKeeper->runIDS();

        # b) block Proxies
        Clansuite_DoorKeeper::blockProxies();

        /**
         *  3) Clear Array, Filter and Assign the $_REQUEST Global to it
         */

        # Clear Parameters Array
        $this->request_parameters   = array();
        $this->get_parameters       = array();
        $this->post_parameters      = array();
        $this->cookie_parameters    = array();

        # Sanitize $_REQUEST
        # $_REQUEST is at first a clone of $_GET, later $_REQUEST, then $_COOKIES are merged; each overwriting former values.
        $this->sanitizeRequest();

        # Assign the GLOBAL $_REQUEST, $_GET, $_POST
        $this->request_parameters = $_REQUEST;
        $this->get_parameters     = $_GET;
        $this->post_parameters    = $_POST;
        $this->cookie_parameters  = $_COOKIE;
    }

    /**
     * Lists all parameters in the specific parameters array
     * Defaults to Request parameters array
     */
    public function getParameterNames($parameterArrayName = 'REQUEST')
    {
        $parameterArrayName = strtoupper($parameterArrayName);

        if($parameterArrayName == 'R' or $parameterArrayName == 'REQUEST')
        {
            return array_keys($this->request_parameters);
        }

        if($parameterArrayName == 'G' or $parameterArrayName == 'GET')
        {
            return array_keys($this->get_parameters);
        }

        if($parameterArrayName == 'P' or $parameterArrayName == 'POST')
        {
            return array_keys($this->post_parameters);
        }

        if($parameterArrayName == 'C' or $parameterArrayName == 'COOKIE')
        {
            return array_keys($this->cookie_parameters);
        }
    }

    /**
     * isset, checks if a certain parameter exists in the parameters array
     *
     * @param string $name Name of the Parameter
     * @param string $parameterArrayName R, G, P, C
     * @return boolean true|false
     */
    public function issetParameter($name, $parameterArrayName = 'REQUEST')
    {
        $parameterArrayName = strtoupper($parameterArrayName);

        if($parameterArrayName == 'R' or $parameterArrayName == 'REQUEST')
        {
            return isset($this->request_parameters[$name]);
        }

        if($parameterArrayName == 'G' or $parameterArrayName == 'GET')
        {
            return isset($this->get_parameters[$name]);
        }

        if($parameterArrayName == 'P' or $parameterArrayName == 'POST')
        {
            return isset($this->post_parameters[$name]);
        }

        if($parameterArrayName == 'C' or $parameterArrayName == 'COOKIE')
        {
            return isset($this->cookie_parameters[$name]);
        }

        return false;
    }

    /**
     * get, returns a certain parameter if existing
     *
     * @param string $name Name of the Parameter
     * @param string $parameterArrayName R, G, P, C
     * @return boolean true|false
     */
    public function getParameter($name, $parameterArrayName = 'REQUEST')
    {
        if(true == $this->issetParameter($name, $parameterArrayName))
        {
            return $this->{strtolower($parameterArrayName).'_parameters'}[$name];
        }
        else
        {
            return null;
        }
    }

    /**
     * Get Value of a specific http-header
     *
     * @todo docblock
     * @param string $name Name of the Parameter
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
     * Get $_SERVER REQUEST_URI
     */
    public function getRequestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get $_SERVER REMOTE_URI
     */
    public function getRemoteURI()
    {
        return $_SERVER['REMOTE_URI'];
    }

    /**
     * Get $_SERVER REMOTE_ADDRESS
     */
    public function getRemoteAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get $_SERVER REQUEST_METHOD
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Gets a Cookie
     *
     * @param string $name Name of the Cookie
     */
    public function getCookie($name)
    {

    }

    /**
     * Check if https is used
     *
     * @access private
     *
     * @return bool
     * @todo by vain: check HTTP_X_FORWARD_PROTO?
     */
    public function isSecure()
    {
        if(isset($_SERVER['HTTPS']) && ( $_SERVER['HTTPS'] = '1' or $_SERVER['HTTPS'] = 'on'))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    /**
     * Checks if a ajax-request is given, by checking
     * X-Requested-With Header for xmlhttprequest.
     *
     * @access public
     *
     * @return bool
     */
    public function isXhr()
    {
       if(strtolower($_SERVER['X-Requested-With']) == 'xmlhttprequest')
       {
           return true;
       }
       else
       {
           return false;
       }
    }

    /**
     * Cleans the global scope of all variables that are found
     * in other super-globals.
     *
     * This code originally from Richard Heyes and Stefan Esser
     *
     * @access private
     *
     * @return void
     */
     private function cleanGlobals()
     {
        # Intercept GLOBALS overwrite
        if ( isset($_REQUEST['GLOBALS']) or isset($_FILES['GLOBALS']) )
        {
            throw new Clansuite_Exception('GLOBALS overwrite attempt detected');
        }

        # List of Variables which shouldn't be unset
        $list = array(
                        'GLOBALS',
                        '_POST',
                        '_GET',
                        '_COOKIE',
                        '_REQUEST',
                        '_SERVER',
                        '_ENV',
                        '_FILES'
                      /**
                       * Notice by vain:
                       * argc+argv are php commandline (CLI) stuff
                       * on an webserver-environment they are found in _SERVER.
                       * CLI is not used, thats why they are commented off.
                       *
                       * If you need them, remove commenting.
                       * and watch out to keep the comma on the start of the next line
                       */
                       #,'argc','argv'
                     );

     	// Create a list of all of the keys from the super-global values.
        // Use array_keys() here to preserve key integrity.
        $keys = array_merge(
                    array_keys($_ENV),
                    array_keys($_GET),
                    array_keys($_POST),
                    array_keys($_COOKIE),
                    array_keys($_SERVER),
                    array_keys($_FILES),
                    // $_SESSION = null if you have not started the session yet.
                    // This insures that a check is performed regardless.
                    isset($_SESSION) && is_array($_SESSION) ? array_keys($_SESSION) : array()
                );

         // Unset the globals.
         foreach ($keys as $key)
         {
            if (isset($GLOBALS[$key]) and !in_array($key, $list) )
            {
                unset($GLOBALS[$key]);
                unset($GLOBALS[$key]);

               /**
                * @todo: 1. check if double unset is still needed !
                * 2. check this PHP critical vulnerability
                * http://www.hardened-php.net/hphp/zend_hash_del_key_or_index_vulnerability.html
                * this is intended to minimize the catastrophic effects that has on systems with
                * register_globals on.. users with register_globals off are still vulnerable but
                * afaik, there is nothing we can do for them.
                */
            }
         }
    }

    /**
     * Essential clean-up of $_REQUEST
     * Handles possible Injections
     *
     * @access private
     * @todo: deprecated
     * @return void
     */
    private function sanitizeRequest()
    {
        # Filter for Request-Parameter: id
        if(isset($_REQUEST['id']) && ctype_digit($_REQUEST['id']))
        {
            $this->parameters['id'] = (int) $_REQUEST['id'];
        }

        # Filter for Request-Parameter: items
        if(isset($_REQUEST['items']) && ctype_digit($_REQUEST['items']))
        {
            $this->parameters['items'] = (int) $_REQUEST['items'];
        }
        
        # Filter for Request-Parameter: defaultCol (Smarty Paginate Get Variable)
        if(isset($_REQUEST['defaultCol']) && ctype_digit($_REQUEST['defaultCol']))
        {
            $this->parameters['defaultCol'] = (int) $_REQUEST['defaultCol'];
        }
        
        # Filter for Request-Parameter: defaultSort (Smarty Paginate Get Variable)
        if(isset($_REQUEST['defaultSort']) && ctype_alpha($_REQUEST['defaultSort']) && (($_REQUEST['defaultSort'] == 'desc') or ($_REQUEST['defaultSort'] == 'asc')) )
        {
            $this->parameters['defaultSort'] = (int) $_REQUEST['defaultSort'];
        }
        
        /**
        $filter = array( '_REQUEST' => $_REQUEST,
                         '_GET'     => $_GET,
                         '_POST'    => $_POST,
                         '_COOKIE'  => $_COOKIE );

        foreach ( $filter as $key => $value )
        {
            $secure = array ( 'id', 'action', 'mod', 'sub', session_name(), 'user_id' );

            foreach( $secure as $s_value )
            {
                if ( isset($value[$s_value]) and $this->check($value[$s_value] , 'is_violent' ) )
                {
                    $security->intruder_alert();
                }
            }

            if( isset($value['id']) )
                $value['id'] = (int) $value['id'];
            if( isset($value['user_id']) )
                $value['user_id'] = (int) $value['user_id'];
            $value['mod']     = isset($value['mod'])    ? $this->check($value['mod']    , 'is_int|is_abc|is_custom', '_') ? $value['mod'] : $cfg->std_module : $cfg->std_module;
            $value['sub']     = isset($value['sub'])    ? $this->check($value['sub']    , 'is_int|is_abc|is_custom', '_') ? $value['sub'] : '' : '';
            $value['action']  = isset($value['action']) ? $this->check($value['action'] , 'is_int|is_abc|is_custom', '_') ? $value['action'] : $cfg->std_module_action : $cfg->std_module_action;

            switch($key)
            {
                case '_REQUEST':
                    $_REQUEST = $value;
                    break;

                case '_GET':
                    $_GET = $value;
                    break;

                case '_POST':
                    $_POST = $value;
                    break;

                case '_COOKIE':
                    $_COOKIE = $value;
                    break;
            }
        }
        */
    }

    /**
     * Revert magic_quotes() if still enabled
     *
     * @param array $var Array to apply the magic quotes fix on
     * @param boolean $sybase Boolean Value TRUE for magic_quotes_sybase
     * @access private
     *
     * @return Returns the magic quotes fixed $var
     */
    private function fix_magic_quotes($var = null, $sybase = null )
    {

        // if no var is specified, fix all affected superglobals
        if (!isset($var) )
        {
            // workaround because magic_quotes does not change $_SERVER['argv']
            $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : null;

            // fix all affected arrays
            foreach (array('_ENV', '_REQUEST', '_GET', '_POST', '_COOKIE', '_SERVER') as $var )
            {
                $GLOBALS[$var] = $this->fix_magic_quotes($GLOBALS[$var], $sybase);
            }

            $_SERVER['argv'] = $argv;

            return true;
        }

        // if var is an array, fix each element
        if (is_array($var) )
        {
            foreach ($var as $key => $val )
            {
                $var[$key] = $this->fix_magic_quotes($val, $sybase);
            }

            return $var;
        }

        // if var is a string, strip slashes
        if (is_string($var) )
        {
            return $sybase ? str_replace('\'\'', '\'', $var) : stripslashes($var);
        }

        // otherwise ignore and just return
        return $var;
    }

    /**
     * Implementation of SPL ArrayAccess
     * only offsetExists and offsetGet are relevant
     */
    public function offsetExists($offset)
    {
        return $this->issetParameter($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getParameter($offset);
    }

    # not setting request vars
    public function offsetSet($offset, $value){}

    # not unsetting request vars
    public function offsetUnset($offset){}
}
?>