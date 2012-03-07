<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
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
    public function issetParameter($name, $arrayname = 'POST');
    public function getParameter($name, $arrayname = 'POST');
    public function expectParameter($parameter, $arrayname);
    public function expectParameters(array $parameters);
    public static function getHeader($name);

    # Direct Access to individual Parameters Arrays
    public function getParameterFromCookie($name);
    public function getParameterFromGet($name);
    public function getParameterFromPost($name);
    public function getParameterFromServer($name);

    # Request Method
    public static function getRequestMethod();
    public static function setRequestMethod($method);
    public static function isAjax();

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
     * @var array Contains the cleaned $_POST Parameters.
     */
    private $post_parameters;

    /**
     * @var array Contains the cleaned $_GET Parameters.
     */
    private $get_parameters;

    /**
     * @var array Contains the cleaned $_COOKIE Parameters.
     */
    private $cookie_parameters;

    /**
     * @var The requestmethod. Possible values are GET, POST, PUT, DELETE.
     */
    protected static $request_method;

    /**
     * @var string the base URL (protocol://server:port)
     */
    protected static $baseURL;

    /**
     * @var boolean for magic_quotes_gpc
     */
    private static $magic_quotes_gpc;

    /**
     * @var object Object with pieces of informations about the target route.
     */
    private static $route;

    /**
     * Construct the Request Object
     *
     * 1) Drop Superglobal $_REQUEST. Just hardcoded reminder for developers to not use it!
     * 2) Intrusion Detection System
     * 3) Additional Security Checks
     * 4) Clear Array, Filter and Assign the $_REQUEST Global to it
     * 5) Detect REST Tunneling through POST and set request_method accordingly
     */
    public function __construct($ids_on = false)
    {
        # 1) Drop $_REQUEST and $GLOBALS. Usage is forbidden!
        unset($_REQUEST);
        unset($GLOBALS);

        if($ids_on === true)
        {
            # 2) Run Intrusion Detection System (on GET, POST, COOKIES)
            $doorKeeper = new Clansuite_DoorKeeper;
            $doorKeeper->runIDS();
        }

        /**
         *  3) Additional Security Checks
         */

        # block XSS
        $_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF']);
        $_SERVER['QUERY_STRING'] = htmlspecialchars($_SERVER['QUERY_STRING']);

        /**
         *  5) Init Parameter Arrays and Assign the GLOBALS
         */

        # Clear Parameters Array
        $this->get_parameters       = array();
        $this->post_parameters      = array();
        $this->cookie_parameters    = array();

        # Assign the GLOBALS $_GET, $_POST, $_COOKIE
        $this->get_parameters     = $_GET;
        $this->post_parameters    = $_POST;
        $this->cookie_parameters  = $_COOKIE;

        /**
         * 6) Detect REST Tunneling through POST and set request_method accordingly
         */
        $this->detectRESTTunneling();
    }

    /**
     * Returns the raw POST Parameters Array.
     * Raw means: no validation, no filtering, no sanitization.
     *
     * @return array POST Parameters Array.
     */
    public function getPost()
    {
        return $this->post_parameters;
    }

    /**
     * Returns the HTTP POST data in raw format via Stream.
     *
     * @return string HTTP POST data (raw).
     */
    public function getPostRaw()
    {
        return file_get_contents('php://input');
    }

    /**
     * Returns the raw GET Parameters Array.
     * Raw means: no validation, no filtering, no sanitization.
     *
     * @return array GET Parameters Array.
     */
    public function getGet()
    {
        return $this->get_parameters;
    }

    /**
     * Returns the COOKIES Parameters Array.
     * Raw means: no validation, no filtering, no sanitization.
     *
     * @return array COOKIES Parameters Array.
     */
    public function getCookies()
    {
        return $this->cookie_parameters;
    }

    /**
     * expectParameters
     *
     * a) isset test          -  to determine if the parameter is incomming
     * b) exception throwing  -  if parameter is not incomming, but expected
     * @todo c) validation          -  validates the incomming parameter via rules
     *
     * $parameters array structure:
     * $parameters = array(
     *  'parametername' => array (      # parametername as key for rules array
     *      'source',                   # (GET|POST)
     *      'validation-rule'
     * );
     * 'modulename' => array ('GET', 'string|lowercase')
     *
     * @example
     * # parameter names only
     * $this->expectParameters(array('modulename','language'));
     * # parameters, one with rules
     * # parameters, all with rules
     *
     * @param array $parameters
     */
    public function expectParameters(array $parameters)
    {
        foreach($parameters as $parameter => $array_or_parametername)
        {
            /**
             * check if we have some rules to process
             */
            if(true === is_array($array_or_parametername))
            {
                $array_name         = $array_or_parametername[0];      # GET|POST|COOKIE
                #$validation_rules   = $array_or_parametername[1];      # some validation commands

                /**
                 * ISSET or Exception
                 */
                $this->expectParameter($parameter, $array_name);

                /**
                 * VALID or Exception
                 */
               #$this->validateParameter($parameter, $validation_rules);

            }
            else # if(is_int($array_or_parametername))
            {
                $this->expectParameter($array_or_parametername);
            }
        }
    }

    /**
     * This method ensures that all the parameters you are expecting
     * and which are required by your action are really incomming with the request.
     * It's a multiple call to issetParameter(), with the difference,
     * that it throws an Exception if not isset!
     *
     * a) isset test          -  to determine if the parameter is incomming
     * b) exception throwing  -  if parameter is not incomming, but expected
     *
     * @param string $parameter
     * @param string $arrayname (GET|POST|COOKIE)
     */
    public function expectParameter($parameter, $arrayname = '')
    {
        # when array is not defined issetParameter will searches (POST|GET|COOKIE)
        if(is_string($arrayname) === true)
        {
            if(false === $this->issetParameter($parameter))
            {
                throw new Clansuite_Exception('Incoming Parameter missing: "' . $parameter . '".');
            }
        }
        else # when array is defined issetParameter will search the given array
        {
            if(false === $this->issetParameter($parameter, $arrayname))
            {
                throw new Clansuite_Exception('Incoming Parameter missing: "' . $parameter . '" in Array "' . $arrayname . '".');
            }
        }
    }

    /**
     * isset, checks if a certain parameter exists in the parameters array
     *
     * @param string $name Name of the Parameter
     * @param string $arrayname GET, POST, COOKIE. Default = GET.
     * @param boolean $where If set to true, method will return the name of the array the parameter was found in.
     * @return mixed|boolean|string arrayname
     *
     */
    public function issetParameter($name, $arrayname = 'GET', $where = false)
    {
        $arrayname = mb_strtoupper($arrayname);

        if( ($arrayname == 'GET' and isset($this->get_parameters[$name])) or isset($this->get_parameters[$name]))
        {
            if($where === false)
            {
                return true;
            }
            else
            {
                return 'get';
            }
        }
        elseif( ($arrayname == 'POST' and isset($this->post_parameters[$name])) or isset($this->post_parameters[$name]))
        {
            if($where === false)
            {
                return true;
            }
            else
            {
                return 'post';
            }
        }
        elseif( ($arrayname == 'COOKIE' and isset($this->cookie_parameters[$name])) or isset($this->cookie_parameters[$name]))
        {
            if($where === false)
            {
                return true;
            }
            else
            {
                return 'cookie';
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * get, returns a certain parameter if existing
     *
     * @param string $name Name of the Parameter
     * @param string $arrayname GET, POST, COOKIE. Default = POST.
     * @param string $default You can set a default value. It's returned if parametername was not found.
     *
     * @return mixed data | null
     */
    public function getParameter($name, $arrayname = 'POST', $default = null)
    {
        /**
         * check if the parameter exists in $arrayname
         * the third property of issetParameter is set to true, so that we get the full and correct array name back
         */
        $parameter_array = $this->issetParameter($name, $arrayname, true);

        /**
         * we use type hinting here to cast the string with array name to boolean
         */
        if((bool) $parameter_array === true)
        {
            # this returns a value from the parameterarray
            return $this->{mb_strtolower($parameter_array).'_parameters'}[$name];
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
     * @param string $name Name of the Parameter
     * @param string $arrayname G, P, C. Default = POST.
     * @return mixed data | null
     */
    public function setParameter($name, $arrayname = 'POST')
    {
        if(true == $this->issetParameter($name, $arrayname))
        {
            return $this->{mb_strtolower($arrayname).'_parameters'}[$name];
        }
        else
        {
            return null;
        }
    }

    /**
     * Shortcut to get a Parameter from $_POST
     *
     * @param string $name Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromPost($name)
    {
        return $this->getParameter($name, 'POST');
    }

    /**
     * Shortcut to get a Parameter from $_GET
     *
     * @param string $name Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromGet($name)
    {
        return $this->getParameter($name, 'GET');
    }

    /**
     * Shortcut to get a Parameter from $_SERVER
     *
     * @param string $name Name of the Parameter
     * @return mixed data | null
     */
    public function getParameterFromServer($name)
    {
        if (in_array($name, array_keys($_SERVER)))
        {
            return $_SERVER[$name];
        }
        else
        {
            return null;
        }
    }

    /**
     * Get previously set cookies.
     *
     * @param string $name Name of the Cookie
     * @return Returns an associative array containing any previously set cookies.
     */
    public function getParameterFromCookie($name)
    {
        if(isset($this->cookie_parameters[$name]) == true)
        {
            return $this->cookie_parameters($name);
        }
    }

    /**
     * Get Value of a specific http-header
     *
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
     * @todo check -> or $_SERVER['SSL_PROTOCOL']
     *
     * @return string
     */
    public static function getServerProtocol()
    {
        if(self::isSecure())
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
        if(isset($_SERVER['REQUEST_URI']))
        {
            return urldecode(mb_strtolower($_SERVER['REQUEST_URI']));
        }

        # MS-IIS and ISAPI Rewrite Filter (only on windows platforms)
        if(isset($_SERVER['HTTP_X_REWRITE_URL']) and stripos(PHP_OS, 'WIN') !== false)
        {
            return urldecode(mb_strtolower($_SERVER['HTTP_X_REWRITE_URL']));
        }

        $p = $_SERVER['SCRIPT_NAME'];
        if(isset($_SERVER['QUERY_STRING']))
        {
            $p .= '?' . $_SERVER['QUERY_STRING'];
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
        $ip = null;

        if(isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = array_pop($ip);
        }
        # NGINX - with natural russian config passes the IP as REAL_IP
        elseif(isset($_SERVER['HTTP_X_REAL_IP']))
        {
            $ip =  $_SERVER['HTTP_X_REAL_IP'];
        }
        elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
        {
            $ip =  $_SERVER['HTTP_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_FORWARDED']))
        {
            $ip = $_SERVER['HTTP_FORWARDED'];
        }
        elseif(isset($_SERVER['HTTP_X_FORWARDED']) )
        {
            $ip =  $_SERVER['HTTP_X_FORWARDED'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if(true === self::validateIP($ip))
        {
            return $ip;
        }
    }

    /**
     * Returns the User Agent ($_SERVER HTTP_USER_AGENT)
     *
     * @return string String denoting the user agent being which is accessing the page.
     */
    public static function getUserAgent()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $ua = strip_tags($ua);
        $ua = filter_var($ua, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        return $ua;
    }

    /**
     * Returns the Referrer ($_SERVER HTTP_REFERER)
     *
     * @return string The address of the page (if any) which referred the user agent to the current page.
     */
    public static function getReferer()
    {
        if(isset($_SERVER['HTTP_REFERER']) === true)
        {
            $refr = $_SERVER['HTTP_REFERER'];
            $refr = strip_tags($refr);
            $refr = filter_var($refr, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        }
        return $refr;
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
     * Get Route returns the static Clansuite_TargetRoute object.
     *
     * With php onbord tools you can't debug this.
     * Please use Clansuite_Debug:firebug($route); to debug.
     * Firebug uses Reflection to show the static properties and values.
     *
     * @return Clansuite_TargetRoute
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Set Route
     *
     * @param $route The route container.
     */
    public static function setRoute($route)
    {
        self::$route = $route;
    }

    /**
     * REST Tunneling Detection
     *
     * This method takes care for REST (Representational State Transfer) by tunneling PUT, DELETE through POST (principal of least power).
     * Ok, this is faked or spoofed REST, but lowers the power of POST and it's short and nice in html forms.
     * @todo consider allowing 'GET' through POST?
     *
     * @see https://wiki.nbic.nl/index.php/REST.inc
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     */
    public function detectRESTTunneling()
    {
        $allowed_rest_methodnames = array('DELETE', 'PUT');

        # request_method has to be POST AND GET has to to have the method GET
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->issetParameter('GET', 'method'))
        {
            # check for allowed rest commands
            if (in_array(mb_strtoupper($_GET['method']), $allowed_rest_methodnames))
            {
                # set the internal (tunneled) method as new REQUEST_METHOD
                self::setRequestMethod($_GET['method']);

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
                throw new Clansuite_Exception('Request Method failure. You tried to tunnel a '.$this->getParameter('method','GET').' request through an HTTP POST request.');
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'GET' and $this->issetParameter('GET', 'method'))
        {
            # NOPE, there's no tunneling through GET!
            throw new Clansuite_Exception('Request Method failure. You tried to tunnel a '.$this->getParameter('method','GET').' request through an HTTP GET request.');
        }
    }

    /**
     * Get the REQUEST METHOD (GET, HEAD, POST, PUT, DELETE)
     *
     * HEAD request is returned internally as GET.
     * The internally set request_method (PUT or DELETE) is returned first,
     * because we might have a REST-tunneling.
     *
     * @return string request method
     */
    public static function getRequestMethod()
    {
        if(isset(self::$request_method))
        {
            return self::$request_method;
        }
        else
        {
            $method = $_SERVER['REQUEST_METHOD'];

            # get method from "http method override" header
            if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']))
            {
                $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
            }

            # add support for HEAD requests, which are GET requests
            if($method == 'HEAD')
            {
                $method = 'GET';
            }

            return $method;
        }
    }

    /**
     * Set the REQUEST_METHOD
     */
    public static function setRequestMethod($method)
    {
        self::$request_method = mb_strtoupper($method);
    }

    /**
     * Checks if a ajax(xhr)-request is given,
     * by checking X-Requested-With Header for XMLHttpRequest.
     *
     * @return boolean true if the request is an XMLHttpRequest, false otherwise
     */
    public static function isAjax()
    {
        if(isset($_SERVER['X-Requested-With']) and $_SERVER['X-Requested-With'] === 'XMLHttpRequest')
        {
            return true;
        }
        elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * is(GET|POST|PUT|DELETE)
     * Boolean "getters" for several HttpRequest Types.
     * This makes request type checking in controllers easy.
     */

    /**
     * Determines, if request is of type GET
     *
     * @return boolean
     */
    public function isGet()
    {
        return (self::$request_method == 'GET');
    }

    /**
     * Determines, if request is of type POST
     *
     * @return boolean
     */
    public function isPost()
    {
        return (self::$request_method == 'POST');
    }

    /**
     * Determines, if request is of type PUT
     *
     * @return boolean
     */
    public function isPut()
    {
        return (self::$request_method == 'PUT');
    }

    /**
     * Determines, if request is of type DELETE
     *
     * @return boolean
     */
    public function isDelete()
    {
        return (self::$request_method == 'DELETE');
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