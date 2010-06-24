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

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Interface for the Request Object
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  HttpRequest
 */
interface Clansuite_Request_Interface
{
    # Parameters
    public function getParameterNames();
    public function issetParameter($parametername, $parameterArrayName = 'REQUEST', $where = false);
    public function getParameter($parametername, $parameterArrayName = 'REQUEST');
    public static function getHeader($name);
    public function getCookie($name);

    # Request Method
    public function getRequestMethod();
    public function setRequestMethod($method);

    # $_SERVER Stuff
    public static function getServerProtocol();
    public static function isSecure();
    public static function getRemoteAddress();
}

/**
 * Clansuite_HttpRequest
 *
 * Purpose:  This is the Clansuite Core Class for Request Handling.
 * It encapsulates the access to sanitized superglobals ($_GET, $_POST, $_SERVER).
 * There are two ways of access (1) via methods and (2) via spl arrayaccess array handling.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  HttpRequest
 */
class Clansuite_HttpRequest implements Clansuite_Request_Interface, ArrayAccess
{
    /**
     * @var array Contains the cleaned $_REQUEST Parameters
     */
    private $request_parameters;

    /**
     * @var array Contains the cleaned $_POST Parameters
     */
    private $post_parameters;

    /**
     * @var array Contains the cleaned $_GET Parameters
     */
    private $get_parameters;

    /**
     * @var array Contains the cleaned $_COOKIE Parameters
     */
    private $cookie_parameters;

    /**
     * @var The requestmethod. Possible values are GET, POST, PUT, DELETE.
     */
    protected $request_method;

    /**
     * @var string the base URL (protocol://server:port)
     */
    protected static $baseURL;

    /**
     * @var boolean for magic_quotes_gpc
     */
    private $magic_quotes_gpc;

    /**
     * Construct the Request Object
     *
     * 1) Intrusion Detection System
     * 2) Filter Globals and Request
     * 3) Additional Security Checks
     * 4) Clear Array, Filter and Assign the $_REQUEST Global to it
     * 5) Detect REST Tunneling through POST and set request_method accordingly
     */
    public function __construct()
    {
        # 1) Run Intrusion Detection System
        $doorKeeper = new Clansuite_DoorKeeper;
        $doorKeeper->runIDS();

        # 2) Filter Globals and Request

        # Reverse the effect of register_globals
        if ((bool) ini_get('register_globals') and mb_strtolower(ini_get('register_globals')) != 'off')
        {
            $this->cleanGlobals();
        }

        # disable magic_quotes_runtime
        @set_magic_quotes_runtime(0);

        # if magic quotes gpc is on, stripslash them
        if ( 1 == get_magic_quotes_gpc() )
        {
            $this->magic_quotes_gpc = true;
            $this->fix_magic_quotes();
            ini_set('magic_quotes_gpc', 0);
        }

        /**
         *  3) Additional Security Checks
         */
        # Block Proxies
        Clansuite_DoorKeeper::blockProxies();

        /**
         *  4) Clear Array, Filter and Assign the $_REQUEST Global to it
         */

        # Clear Parameters Array
        $this->request_parameters   = array();
        $this->get_parameters       = array();
        $this->post_parameters      = array();
        $this->cookie_parameters    = array();

        # Sanitize
        $this->sanitizeRequest();

        # Assign the GLOBALS $_REQUEST, $_GET, $_POST, $_COOKIE
        $this->request_parameters = $_REQUEST;
        $this->get_parameters     = $_GET;
        $this->post_parameters    = $_POST;
        $this->cookie_parameters  = $_COOKIE;

        /**
         * 5) Detect REST Tunneling through POST and set request_method accordingly
         */
        $this->detectRESTTunneling();
    }

    /**
     * Shorthand for boolean check of the requestMethod GET
     *
     * @return boolean true | false
     */
    public function isGet()
    {
        if($this->requestMethod == 'GET')
        {
            return true;
        }
        return false;
    }

    /**
     * Shorthand for boolean check of the requestMethod POST
     *
     * @return boolean true | false
     */
    public function isPost()
    {
        if($this->requestMethod == 'POST')
        {
            return true;
        }
        return false;
    }

    /**
     * Shorthand for boolean check of the requestMethod PUT
     *
     * @return boolean true | false
     */
    public function isPut()
    {
        if($this->requestMethod == 'PUT')
        {
            return true;
        }
        return false;
    }

    /**
     * Shorthand for boolean check of the requestMethod DELETE
     *
     * @return boolean true | false
     */
    public function isDelete()
    {
        if($this->requestMethod == 'DELETE')
        {
            return true;
        }
        return false;
    }

    /**
     * Lists all parameters in the specific parameters array
     * Defaults to Request parameters array
     *
     * @param string $parameterArrayName R, G, P, C (REQUEST, GET, POST, COOKIE)
     * @return array
     */
    public function getParameterNames($parameterArrayName = 'REQUEST')
    {
        $parameterArrayName = mb_strtoupper($parameterArrayName);

        if(in_array($parameterArrayName, $this->{mb_strtolower($parameterArrayName).'_arraynames'}))
        {
            return array_keys($this->{mb_strtolower($parameterArrayName).'_parameters'});
        }
        else
        {
            return null;
        }
    }

    /**
     * isset, checks if a certain parameter exists in the parameters array
     *
     * @param string $parametername Name of the Parameter
     * @param string $parameterArrayName R, G, P, C
     * @param boolean $where If set to true, method will return the name of the array the parameter was found in.
     * @return mixed | boolean true|false | string arrayname
     */
    public function issetParameter($parametername, $parameterArrayName = 'REQUEST', $where = false)
    {
        $parameterArrayName = mb_strtoupper($parameterArrayName);

        if(in_array($parameterArrayName, array ('R', 'REQUEST')) and isset($this->request_parameters[$parametername]))
        {
            if($where == false)
            {
                return true;
            }
            else
            {
                return 'request';
            }
        }

        if(in_array($parameterArrayName, array ('P', 'POST')) and isset($this->post_parameters[$parametername]))
        {
            if($where == false)
            {
                return true;
            }
            else
            {
                return 'post';
            }
        }

        if(in_array($parameterArrayName, array ('G', 'GET')) and isset($this->get_parameters[$parametername]))
        {
            if($where == false)
            {
                return true;
            }
            else
            {
                return 'get';
            }
        }

        if(in_array($parameterArrayName, array ('C', 'COOKIE')) and isset($this->cookie_parameters[$parametername]))
        {
            if($where == false)
            {
                return true;
            }
            else
            {
                return 'cookie';
            }
        }

        return false;
    }

    /**
     * get, returns a certain parameter if existing
     *
     * @param string $parametername Name of the Parameter
     * @param string $parameterArrayName R, G, P, C
     * @param string $default You can set a default value. It's returned if parametername was not found.
     *
     * @return mixed data | null
     */
    public function getParameter($parametername, $parameterArrayName = 'REQUEST', $default = null)
    {
        /**
         * check if the parameter exists in $parameterArrayName
         * the third property of issetParameter is set to true, so that we get the full and correct array name back
         * even if shortcut like R, G, P or C ($parameterArrayName) was used.
         */
        $parameter_array = $this->issetParameter($parametername, $parameterArrayName, true);

        /**
         * we use type hinting here to cast the string with array name to boolean
         */
        if((bool) $parameter_array == true)
        {
            # this returns a value from the parameterarray
            return $this->{mb_strtolower($parameter_array).'_parameters'}[$parametername];
        }
        elseif($default !== null)
        {
            # this returns the default value,incomming via method property $default
            return $default;
        }
        else
        {
            return null;
        }
    }

    /**
     * set, returns a certain parameter if existing
     *
     * @param string $parametername Name of the Parameter
     * @param string $parameterArrayName R, G, P, C
     * @return mixed data | null
     */
    public function setParameter($parametername, $parameterArrayName = 'REQUEST')
    {
        if(true == $this->issetParameter($parametername, $parameterArrayName))
        {
            return $this->{mb_strtolower($parameterArrayName).'_parameters'}[$parametername];
        }
        else
        {
            return null;
        }
    }

    /**
     * Shortcut to get a Parameter from $_POST
     *
     * @param string $parametername Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromPost($parametername)
    {
        return $this->getParameter($parametername, 'POST');
    }

    /**
     * Shortcut to get a Parameter from $_GET
     *
     * @param string $parametername Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromGet($parametername)
    {
        return $this->getParameter($parametername, 'GET');
    }

    /**
     * Shortcut to get a Parameter from $_SERVER
     *
     * @param string $parametername Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromServer($parametername)
    {
        if (in_array($parametername, array_keys($_SERVER)))
        {
            return $_SERVER[$parametername];
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
     * @return string
     */
    public static function getHeader($name)
    {
        $name = 'HTTP_' . mb_strtoupper(str_replace('-','_', $name));
        if (isset($_SERVER[$name]))
        {
            return $_SERVER[$name];
        }
        return null;
    }

    /**
     * Determine Type of Protocol for Webpaths (http/https)
     * Get for $_SERVER['HTTPS']
     *
     * @todo check $_SERVER['SSL_PROTOCOL'] + $_SERVER['HTTP_X_FORWARD_PROTO']?
     * @return string
     */
    public static function getServerProtocol()
    {
        if(self::isSecure()) # @todo check -> or $_SERVER['SSL_PROTOCOL']
        {
             return 'https://';
        }
        else
        {
             return 'http://';
        }
    }

    /**
     * Determine Type of Protocol for Webpaths (http/https)
     * Get for $_SERVER['HTTPS'] with boolean return value
     *
     * @todo check about $_SERVER['SERVER_PORT'] == 443, is this always ssl then?
     * @see $this->getServerProtocol()
     * @return bool
     */
    public static function isSecure()
    {
        if(isset($_SERVER['HTTPS']) and (mb_strtolower($_SERVER['HTTPS']) === 'on' or $_SERVER['HTTPS'] == '1') )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Determine Port Number for Webpaths (http/https)
     * Get for $_SERVER['SERER_PORT'] and $_SERVER['SSL_PROTOCOL']
     * @return string
     */
    private static function getServerPort()
    {
        if ( isset($_SERVER['HTTPS']) == false and $_SERVER['SERVER_PORT'] != 80 or isset($_SERVER['HTTPS']) and $_SERVER['SERVER_PORT'] != 443 )
        {
            return ':'.$_SERVER['SERVER_PORT'];
        }
    }

    /**
     * Returns the base of the current URL
     * Format: protocol://server:port
     *
     * The "template constant"" WWW_ROOT is later defined as getBaseURL
     * <form action="<?=WWW_ROOT?>/news/7" method="DELETE"/>
     *
     * @return string
     */
    public static function getBaseURL()
    {
        if( empty(self::$baseURL) )
        {
            # 1. Determine Protocol
            self::$baseURL = self::getServerProtocol();

            # 2. Determine Servername
            self::$baseURL .= self::getServerName();

            # 3. Determine Port
            self::$baseURL .= self::getServerPort();
        }

        return self::$baseURL;
    }

    /**
     * Get $_SERVER SERVER_NAME
     *
     * @return string The name of the server host under which the current script is executing.
     */
    public static function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Get $_SERVER REQUEST_URI
     *
     * @return string The URI which was given in order to access this page; for instance, '/index.html'.
     */
    public static function getRequestURI()
    {
        if (isset($_SERVER['REQUEST_URI']))
        {
            return urldecode(mb_strtolower($_SERVER['REQUEST_URI']));
        }

        # MS-IIS and ISAPI Rewrite Filter
        if ($_SERVER['HTTP_X_REWRITE_URL'])
        {
            return urldecode(mb_strtolower($_SERVER['HTTP_X_REWRITE_URL']));
        }

        $p = $_SERVER['SCRIPT_NAME'];
        if ($_SERVER['QUERY_STRING'])
        {
            $p .= '?'.$_SERVER['QUERY_STRING'];
        }

        return urldecode(mb_strtolower($p));
    }

    /**
     * Get $_SERVER REMOTE_URI
     *
     * @return string
     */
    public static function getRemoteURI()
    {
        return $_SERVER['REMOTE_URI'];
    }

    /**
     * Get $_SERVER QUERY_STRING
     *
     * @return string The query string via which the page was accessed.
     */
    public static function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * Get the current Url
     *
     * @return string Returns the current URL, which is the HOST + REQUEST_URI, without index.php.
     */
    public static function getCurrentUrl()
    {
        return str_replace('/index.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    /**
     * Get IP = $_SERVER REMOTE_ADDRESS
     *
     * @return string The IP/HOST from which the user is viewing the current page.
     */
    public static function getRemoteAddress()
    {
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) and self::validateIP($_SERVER['HTTP_CLIENT_IP']))
        {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
        {
            foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $ip)
            {
                if (self::validateIP(trim($ip)))
                {
                    return $ip;
                }
            }
        }

        # NGINX - with natural russian config passes the IP as REAL_IP ;)
        if (array_key_exists('HTTP_X_REAL_IP', $_SERVER) and self::validateIP($_SERVER['HTTP_X_REAL_IP']))
        {
            return $_SERVER['HTTP_X_REAL_IP'];
        }

        if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) and self::validateIP($_SERVER['HTTP_FORWARDED_FOR']))
        {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_FORWARDED', $_SERVER) and self::validateIP($_SERVER['HTTP_FORWARDED']))
        {
            return $_SERVER['HTTP_FORWARDED'];
        }

        if (array_key_exists('HTTP_X_FORWARDED', $_SERVER) and self::validateIP($_SERVER['HTTP_X_FORWARDED']))
        {
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        else
        {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Get $_SERVER HTTP_USER_AGENT
     *
     * @return string String denoting the user agent being which is accessing the page.
     */
    public static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Get $_SERVER HTTP_REFERER
     *
     * @return string The address of the page (if any) which referred the user agent to the current page.
     */
    public static function getReferer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Validates a given IP
     *
     * @see getRemoteAddress()
     * @param string $ip The IP address to validate.
     * @param boolen $ipv6 Boolean true, activates ipv6 checking.
     * @return boolean True, if IP is valid. False, otherwise.
     */
    public static function validateIP($ip, $ipv6 = false)
    {
        if (true === $ipv6)
        {
            return (bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        }
        else
        {
            return (bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_IPV4);
        }
    }


    /**
     * This method takes care for REST (Representational State Transfer) by tunneling PUT, DELETE through POST (principal of least power).
     * Ok, this is faked or spoofed REST, but lowers the power of POST and it's short and nice in html forms.
     * @see https://wiki.nbic.nl/index.php/REST.inc
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public function detectRESTTunneling()
    {
        # this will allow DELETE and PUT
        $REST_MethodNames = array('DELETE', 'PUT');  # @todo allow 'GET' through POST?

        # request_method has to be POST AND GET has to to have the method GET
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_GET['method']))
        {
            # check for allowed rest commands
            if (in_array(mb_strtoupper($_GET['method']), $REST_MethodNames))
            {
                # set the internal (tunneled) method as new REQUEST_METHOD
                $this->setRequestMethod($_GET['method']);

                # unset the tunneled method
                unset($_GET['method']);

                # now strip the methodname from the QUERY_STRING and rebuild REQUEST_URI

                # rebuild the QUERY_STRING from $_GET
                $_SERVER['QUERY_STRING'] = http_build_query($_GET);
                # rebuild the REQUEST_URI
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
                # append QUERY_STRING to REQUEST_URI if not empty
                if ($_SERVER['QUERY_STRING'] != '')
                {
                    $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
            else
            {
                throw Clansuite_Exception('Request Method failure. You tried to tunnel a '.$this->getParameter('method','GET').' request through an HTTP POST request.');
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'GET' and isset($_GET['method'])) # $this->issetParameter('GET', 'method')
        {
            # NOPE, there's no tunneling through GET!
            throw Clansuite_Exception('Request Method failure. You tried to tunnel a '.$this->getParameter('method','GET').' request through an HTTP GET request.');
        }
    }

    /**
     * Get the REQUEST METHOD
     * Returns the internal request method first, then $_SERVER REQUEST_METHOD.
     *
     * @return string request method
     */
    public function getRequestMethod()
    {
        # first get the internally set request_method (PUT or DELETE) because we might have a REST-tunneling
        if(isset($this->request_method))
        {
            return $this->request_method;
        }
        else # this will be POST or GET
        {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    /**
     * Set the REQUEST_METHOD
     */
    public function setRequestMethod($method)
    {
        $this->request_method = mb_strtoupper($method);
    }

    /**
     * Get previously set cookies.
     *
     * @param string $name Name of the Cookie
     * @return Returns an associative array containing any previously set cookies.
     */
    public function getCookie($name)
    {
        if(isset($this->cookie_parameters[$name]) == true)
        {
            return $this->cookie_parameters($name);
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
        if(isset($_SERVER['X-Requested-With']) and mb_strtolower($_SERVER['X-Requested-With']) === 'xmlhttprequest')
        {
            return true;
        }
        elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Shorthand for isXhr()
     *
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isXhr();
    }

    /**
     * Cleans the global scope of all variables that are found
     * in other super-globals.
     *
     * This code originally from Richard Heyes and Stefan Esser
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
                #'GLOBALS',
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
                isset($_SESSION) and is_array($_SESSION) ? array_keys($_SESSION) : array()
        );

        // Unset the globals.
        foreach ($keys as $key)
        {
            if (isset($GLOBALS[$key]) and in_array($key, $list) == false )
            {
                unset($GLOBALS[$key]);
            }
        }
    }

    /**
     * Handles possible Injections and clean up of $_REQUEST
     */
    private function sanitizeRequest()
    {
        # Filter for Request-Parameter: id
        if(isset($_REQUEST['id']) and ctype_digit($_REQUEST['id']))
        {
            $this->parameters['id'] = (int) $_REQUEST['id'];
        }

        # Filter for Request-Parameter: items
        if(isset($_REQUEST['items']) and ctype_digit($_REQUEST['items']))
        {
            $this->parameters['items'] = (int) $_REQUEST['items'];
        }

        # Filter for Request-Parameter: defaultCol (Smarty Paginate Get Variable)
        if(isset($_REQUEST['defaultCol']) and ctype_digit($_REQUEST['defaultCol']))
        {
            $this->parameters['defaultCol'] = (int) $_REQUEST['defaultCol'];
        }

        # Filter for Request-Parameter: defaultSort (Smarty Paginate Get Variable)
        if(isset($_REQUEST['defaultSort']) and ctype_alpha($_REQUEST['defaultSort']) and (($_REQUEST['defaultSort'] == 'desc') or ($_REQUEST['defaultSort'] == 'asc')) )
        {
            $this->parameters['defaultSort'] = (int) $_REQUEST['defaultSort'];
        }
    }

    /**
     * Revert magic_quotes() if still enabled
     * stripslashes + array_deep + non_recursive
     *
     * This while-loop is an replacement for the old recursive method.
     * It's taken from "Guide to PHP Security" by Ilia Alshanetsky.
     * @link http://talks.php.net/show/php-best-practices/26
     *
     * @param array $var Array to apply the magic quotes fix on
     * @return Returns the magic quotes fixed $var
     */
    private function fix_magic_quotes($input = null)
    {
        if($this->magic_quotes_gpc == false)
        {
            return $input;
        }

        // if no var is specified, fix all affected superglobals
        if ( isset($input) == false )
        {
            $input = array($_ENV, $_REQUEST, $_GET, $_POST, $_COOKIE, $_SERVER);
        }

        $k = null;
        $v = null;

        while ( list($k,$v) = each($input) )
        {
            foreach ($v as $key => $val)
            {
                if (is_array($val) == false)
                {
                    $input[$k][$key] = stripslashes($val);

                    continue;
                }
                $input[] =& $input[$k][$key];
            }
        }
        unset($input);
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
    public function offsetSet($offset, $value)
    {
        return;
    }

    # not unsetting request vars
    public function offsetUnset($offset)
    {
        return;
    }
}
?>