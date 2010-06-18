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
if(defined('IN_CS') === false)
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
    /**
     * Constructor.
     *
     * @param string $request_url The Request URL incomming via Clansuite_HttpRequest::getRequestURI()
     */
    public function __construct($request_uri)
    {
        $this->uri = self::prepareRequestURI($request_uri);
    }

    public function addRoute($name, array $route)
    {

    }

    public function addRoutes(array $routes)
    {

    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function delRoute($name)
    {
        unset($this->routes[$name]);
    }

    public function generateURL($action, $args = null, $params = null, $fragment = null)
    {

    }

    /**
     * Main method of Clansuite_Router
     *
     * The workflow is
     * 1. check if modrewrite_on, this decides on the url_parser to use
     * 2. url_parser splits the uri into uri segments
     * 3. routes are initalized (the defaultRoute + all moduleRoutes)
     * 4. try to find a route/map matching with the uri_segments
     * 5. if no mapping applies, set default values from config and fallback to a static routing
     * 6. always! -> found_route -> call!
     */
    public function route()
    {
        # detects if RewriteEngine is active and calls the proper URLParser for extraction of uri segments
        if(true == $this->isRewriteEngineOn())
        {
            $this->uriParser_Rewrite($this->uri);
        }
        else # default
        {
            $this->uriParser_NoRewrite($this->uri);
        }

        # attach more routes to this object via the event "onInitializeRoutes"
        Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        # initalize Routes
        $this->initDefaultRoutes();
        #$this->initModuleRoutes();

        # map match uri

    }

    /**
     * Ensures Apache "RewriteEngine on" by performing two checks
     * a) check if Apache Modules "mod_rewrite" is loaded/enabled
     * b) check if Rewrite Engine is enabled in .htaccess"
     *
     * In case both checks are true, a modrewrite flag file is written.
     * This reduces read operations on the ".htaccess" file.
     * The next time this function is called, only the a flag-file-check is performed.
     *
     * @return bool True, if "RewriteEngine On". False otherwise.
     */
    public static function isRewriteEngineOn()
    {
        # maybe, a "modrewrite on" flag file exists, so we don't read htaccess every time
        if(is_file(ROOT . 'configuration/modrewrite_active.flag') === true)
        {
            return true;
        }

        # ensure apache has module mod_rewrite active
        if(function_exists('apache_get_modules') and in_array('mod_rewrite', apache_get_modules()))
        {
            # load htacces and check if RewriteEngine is enabled
            $htaccess_content = @file_get_contents(ROOT . '.htaccess');
            $rewriteEngineOn = preg_match('/.*[^#][\t ]+RewriteEngine[\t ]+On/i', $htaccess_content);

            if($rewriteEngineOn == 1)
            {
                # because this setup will hardly change often, we write a flag file
                @file_put_contents(ROOT . 'configuration/modrewrite_active.flag', $rewriteEngineOn);

                return true;
            }
            else # RewriteEngine not set or commented off in htaccess
            {
                return false;
            }
        }
        else # Apache Mod_Rewrite not available
        {
            return false;
        }
    }

    /**
     * Get and prepare the SERVER_URL/URI
     *
     * Several fixes are applied the $request_url (which is Clansuite_HttpRequest::getRequestURI())
     * It's already (1) lowercased and (2) urldecoded when incomming.
     * This function (3) strips slashes from beginning and end and (4) prepends a slash.
     * A multislash removal is not needed, because of the later usage of preg_split.
     *
     * @param string $reuest_uril this is basically Clansuite_HttpRequest::getRequestURI
     *
     * @return string Request URL
     */
    private function prepareRequestURI($request_uri)
    {
        $this->uri = '/' . trim($request_uri, '/');

        Clansuite_Debug::firebug('The initial Server Request URI is "' . $this->uri . '"');

        return $this->uri;
    }

    /**
     * URLParser for NoRewrite URL/URIs
     *
     * This URLParser has to extract mod, sub, action, id/parameters from the URI.
     *
     * @param string $url The Request URL
     */
    private function UrlParser_NoRewrite($uri) # Standard_Request_Resolver
    {

        # use some parse_url magic to get the url_query part from the uri
        $uri_query_string = parse_url($uri, PHP_URL_QUERY);
        unset($uri);

        /**
         * The ampersand (&)
         */
        $uri_query_array = explode('&', $uri_query_string);

        /**
         * The equals sign (=)
         *
         * This addresses the pair relationship between parameter name and (=) value.
         */
        $parameters = array();
        foreach($uri_query_array as $query_pair)
        {
            list($key, $value) = explode('=', $query_pair);
            $parameters[$key] = $value;
        }
        unset($uri_query_string, $uri_query_array);

        /**
         * Finished!
         */
        $this->uri_segments = $parameters;
    }

    /**
     * URLParser for Apache ModRewrite URL/URIs
     *
     * This is based on htaccess rewriting with [QSA,L].
     * Think of it as a Modrewrite_Request_Resolver.
     *
     * Maybe rewriting with [E] would allow a much faster url parsing by string splitting,
     * because of a fixed url style (think of @ or ___ as separators).
     * @todo consider using rewriting with [E] in htaccess and string splitting
     *
     * @param string $url The Request URL
     */
    public function UrlParser_Rewrite($uri)
    {
        /**
         * The query string up to the question mark (?)
         *
         * Checks if url string contains "?" and removes everything before the "?".
         * Example: when you have "index.php?...", this strips of "index.php?"
         * Note: with correct rewrite rules in htaccess, this conditon is not needed.
         */
        $pos = mb_strpos($uri, '?');
        if($pos !== false)
        {
            $uri = mb_substr($uri, 0, $pos);
        }
        unset($pos);

        /**
         * The last dot (.)
         *
         * This detects the extension and removes it from the uri string.
         * The extension determines the output format. It is always the last piece of the URI.
         * Even if there are multiple points in the url string this processes only the last dot
         * and fetches everything after it as the extension.
         */
        $pos = mb_strpos($uri, '.');
        if($pos !== false)
        {
            $uri_dot_array = array();
            # Segmentize the url into an array
            $uri_dot_array = explode('.', $url);
            # chop off the last piece as the extension
            $this->extension = array_pop($uri_dot_array);
            # there might be multiple dots in the url
            # thats why implode is used to reassemble the segmentized array to a string again
            # but note the different glue string: the dots are now replaced by slashes ,)
            # = ini_get('arg_separator.output')
            $uri = implode('/', $uri_dot_array);
        }
        unset($uri_dot_array);
        unset($pos);

        /**
         * The slashes (/) and empty segments
         */
        $url_split = preg_split('#/#', $uri, -1, PREG_SPLIT_NO_EMPTY);

        unset($uri);

        /**
         * Finished!
         */
        #Clansuite_Debug::firebug($uri_split);
        $this->uri_segments = $url_split;
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