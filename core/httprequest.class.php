<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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

//Security Handler
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

    public function getRequestMethod();
    public function getCookie($name);
    public function isSecure();

    #public function getAuthData();
    public function getRemoteAddress();
}

/**
 * httprequest
 *
 * Request class for encapsulating access to the superglobal $_REQUEST.
 * There are two ways of access:
 * (1) via methods and (2) via spl arrayaccess array handling.
 *
 * @todo split $_REQUEST into GET and POST with each seperate access methods
 *
 */
class httprequest implements RequestInterface, ArrayAccess
{
    #
    private $parameters;

    /**
     * 1) Filter Globals and Request
     * 2) Import SUPERGLOBAL $_REQUEST into $parameters
     */
    public function __construct()
    {
        // Reverse the effect of register_globals
        if (ini_get('register_globals'))
        {
            $this->cleanGlobals();
        }
        #$this->cleanup_request();
        $this->fix_magic_quotes();

        # 2) Clear Array and Assign the $_REQUEST Global to it
        $this->parameters = array();
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
     * @todo docblock
     * @param
     */
    public function issetParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * get, returns a certain parameter if existing
     *
     * @todo docblock
     * @param
     */
    public function getParameter($name)
    {
        if (isset($this->parameters[$name]))
        {
            return strtolower($this->parameters[$name]);
        }
    }

    /**
     * Get Value of a specific http-header
     *
     * @todo docblock
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
     * Get $_SERVER REMOTE_ADDRESS
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Gets a Cookie
     */
    public function getCookie($name)
    {

    }

    /**
     * Check if https is used
     *
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
     * @return void
     */
     private function cleanGlobals()
     {
        // @todo: change this to a nicer error
        if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
            die('GLOBALS overwrite attempt detected');
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
                       * hats why they are commented off
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
            if (isset($GLOBALS[$key]) && ! in_array($key, $list))
            {
                unset($GLOBALS[$key]);
                unset($GLOBALS[$key]); # no, this is not a bug, we use double unset() .. it is to circunvent
              /* *
                                    * @todo: check if this is still a vulnerability !
                                    *this PHP critical vulnerability
                                    * http://www.hardened-php.net/hphp/zend_hash_del_key_or_index_vulnerability.html
                                    * this is intended to minimize the catastrophic effects that has on systems with
                                    * register_globals on.. users with register_globals off are still vulnerable but
                                    * afaik,there is nothing we can do for them.
                                    */
            }
         }
    }

    /**
     * Essential clean-up of $_REQUEST
     * Handles possible Injections
     */
    private function cleanup_request()
    {
        #global $cfg, $security;

        $filter = array( '_REQUEST' => $_REQUEST, '_GET' => $_GET, '_POST' => $_POST, '_COOKIE' => $_COOKIE );
        foreach ( $filter as $key => $value )
        {
            $secure = array ( 'id', 'action', 'mod', 'sub', $cfg->session_name, 'user_id' );
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
    }

     /**
     * Revert magic_quotes() if still enabled
     * @access public static
     */
    private function fix_magic_quotes($var = NULL, $sybase = NULL )
    {
        // if sybase style quoting isn't specified, use ini setting
        if (!isset($sybase) )
        {
            $sybase = ini_get('magic_quotes_sybase');
        }

        // if no var is specified, fix all affected superglobals
        if (!isset($var) )
        {
            // if magic quotes is enabled
            if (get_magic_quotes_gpc() )
            {
                // workaround because magic_quotes does not change $_SERVER['argv']
                $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : NULL;

                // fix all affected arrays
                foreach (array('_ENV', '_REQUEST', '_GET', '_POST', '_COOKIE', '_SERVER') as $var )
                {
                    $GLOBALS[$var] = $this->fix_magic_quotes($GLOBALS[$var], $sybase);
                }

                $_SERVER['argv'] = $argv;

                // turn off magic quotes
                // so scripts which require this setting will work correctly
                ini_set('magic_quotes_gpc', 0);
            }

            // disable magic_quotes_sybase
            if ($sybase )
            {
                ini_set('magic_quotes_sybase', 0);
            }

            // disable magic_quotes_runtime
            set_magic_quotes_runtime(0);
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