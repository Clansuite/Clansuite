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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
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
 * Examples: map a logical URL (a mod_rewritten one) to a Controller/Method/Parameter
 * or map a FileRequest via logical URL (a mod_rewritten one) to a DownloadController/Method/Parameters
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
class Clansuite_Router implements ArrayAccess, Clansuite_Router_Interface
{
    /**
     * @var object Clansuite_Config
     */
    private $config;

    private static $use_cache = false;

    private $uri = '';
    private $uri_segments = array();
    private static $extension = '';

    /**
     * Routing Table
     *
     * @var array Routes Array
     */
    private $routes = array();

    /**
     * Constructor.
     */
    public function __construct(Clansuite_HttpRequest $request, Clansuite_Config $config)
    {
        # set config object to the router for later access to config variables
        $this->config = $config;

        # fetch the (unmodified) Request URI from HttpRequest Object
        $request_uri = $request->getRequestURI();

        # clean the incomming uri and set to class
        $this->uri = self::prepareRequestURI($request_uri);
    }

    /**
     * Add route
     *
     * @param string $url_pattern
     * @param array $route_options
     */
    public function addRoute($url_pattern, array $route_options = null)
    {
        /**
         * 1) Preprocess the route
         */
        # split the pattern describing the URL target into uri segments
        $url_pattern = ltrim($url_pattern, '/');
        $segments = explode('/', $url_pattern);

        # the incomming route might have placeholders lile (:num) or (:id)
        $url_pattern = self::placeholdersToRegexp($url_pattern);

        $regexp = $this->processSegmentsRegExp($segments, $route_options);
        $options = array('regexp' => $regexp,
                         'number_of_segments' => count($segments));


        /**
         * 2) Finally add the *now preprocessed* Route.
         */
        $this->routes[$url_pattern] = $options;
    }

    public function processSegmentsRegExp(array $segments, array $requirements = null)
    {
        # start regular expression
        $regexp = '/^';

        # process all segments
        foreach($segments as $segment)
        {
            $match = '';

            /**
             * process static named parameter => ":contoller"
             */
            if (true === preg_match('/^:([a-zA-Z_]+)$/', $segment, $match))
            {
                $name = $match[1]; #controller

                # is there a requirement for this param?
                if(true === isset($requirements[$name]))
                {
                    # add it to the regex
                    $regexp .= '\/(?P<' . $name . '>' . $requirements[$name] . ')';
                    # and remove the requirement
                    unset($requirements[$name]);
                }
                else # no requirement
                {
                    $regexp .= '(?P<' . $name . '>[a-z0-9_-]+)';
                }
            }
            else # process static parameter = string => "/index" or "/news"
            {
                $regexp .= '\\/' . $segment;
            }

            # regexp between segments
            $regexp .= '\/?';
        }

        # finish regular expression
        $regexp .= '$/';

        return $regexp;
    }

    /**
     * Add multiple route
     *
     * @param array $routes Array with multiple routes.
     */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route => $options)
        {
            $this->addRoute( (string) $route, (array) $options);
        }
    }

    /**
     * Method returns all loaded routes.
     *
     * @return array Returns array with all loaded Routes.
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Delete a route by its url pattern
     *
     * @param string $url_pattern
     */
    public function delRoute($url_pattern)
    {
        unset($this->routes[$url_pattern]);
    }

    /**
     * Generates a URL by parameters.
     *
     * @param string $url_pattern The URL Pattern of the route
     * @param array  $params An array of parameters
     * @param bool   $absolute Whether to generate an absolute URL
     *
     * @return string The generated (relative or absolute) URL.
     */
    public function generateURL($url_pattern, array $params = null, $absolute = false)
    {
        # @todo merge with buildURL + routing rules + parameters
    }

    /**
     * Builds a url string
     *
     * @param $urlstring String to build the url from (e.g. '/news/admin/show')
     * @param $internal_url bool True (default) defines ampersand type as "amp"; False as "&".
     */
    public static function buildURL($urlstring, $internal_url = true)
    {
        # if urlstring is already a qualified url
        if(false !== strpos($urlstring, WWW_ROOT . 'index.php?mod='))
        {
            # there is no need to rebuild it, just return
            return $urlstring;
        }
        # e.g. ROOT/news/admin
        #elseif(REWRITE_ENGINE_ON === true)
        #{
            #Clansuite_Debug::firebug(WWW_ROOT . ltrim($urlstring, '/'));
           #return WWW_ROOT . ltrim($urlstring, '/');
        #}
        else # ROOT/index.php?mod=abc&action=123&etc...
        {
            $url_values = explode('/', ltrim($urlstring, '/'));
            $url_keys = array('mod', 'sub', 'action', 'id');
            $url_data = Clansuite_Functions::array_unequal_combine($url_keys, $url_values);
            $url = '';

            # Defaults to &amp; for internal usage in html documents.
            #  = ROOT/index.php?mod=abc&amp;action=123&amp;etc...
            if($internal_url === true)
            {
                $url = http_build_query($url_data, '', '&amp;');
            }
            # external link / redirect etc.
            #  = ROOT/index.php?mod=abc&action=123&etc...
            elseif($internal_url === false)
            {
                $url = http_build_query($url_data, '', '&');
            }

            #Clansuite_Debug::firebug(WWW_ROOT . 'index.php?' . $url);
            return WWW_ROOT . 'index.php?' . $url;
        }
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
        if(true === $this->isRewriteEngineOn() and empty($_GET['mod']) and empty($_GET['sub']))
        {
            $this->UrlParser_Rewrite($this->uri);
        }
        else # default
        {
            $this->UrlParser_NoRewrite($this->uri);
            return $this->NoRewriteRoute();
        }

        /**
         * If there are no uri segments, loading routes and matching is pointless.
         * Dispatch to the default route!
         */
        if(empty($this->uri) or $this->uri === '/')
        {
            Clansuite_TargetRoute::setController($this->config['defaults']['module']);
            Clansuite_TargetRoute::setAction($this->config['defaults']['action']);

            if(Clansuite_TargetRoute::dispatchable() === true)
            {
                return Clansuite_TargetRoute::getInstance();
            }
        }

        # attach more routes to this object via the event "onInitializeRoutes"
        #Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        # initalize Routes
        $this->loadDefaultRoutes();

        # first filter: drop all routes with more segments then uri_segments
        self::removeRoutesBySegmentCount();

        # map match uri
        return $this->mapMatchURI();
    }

    /**
     * Matches the URI against the Routes Table
     * takes static, dynamic and regexp routings into account
     *
     * @return object Clansuite_TargetRoute
     */
    public function mapMatchURI()
    {
        #Clansuite_Debug::firebug($this->uri);

        /**
         * Do we have a direct match ?
         * URI = '/news/show' => Routes['/news/show']
         */
        if(isset($this->routes[$this->uri]) === true)
        {
            $found_route = '';
            $found_route = $this->routes[$this->uri];
        }
        else # no, there wasn't a 1:1 match. now we have to check the uri segments
        {
            # loop over the remaining routes and try to map match the uri_segments
            foreach($this->routes as $route_pattern => $route_values)
            {
                unset($route_pattern);

                #Clansuite_Debug::printR($route_values);

                $matches = '';

                /**
                 * process static named parameter
                 * like ":controller" or ":subcontroller" or ":action" or ":id"
                 * $route_pattern
                 */
                if (true === preg_match('/^:([a-zA-Z_]+)$/', $this->uri, $matches))
                {
                    #Clansuite_Debug::firebug($matches);
                    # $found_route = $matches[1];
                    # @todo
                    Clansuite_TargetRoute::setController($matches[1]);
                }
                # dynamic regexp segment?
                elseif(true === preg_match( $route_values['regexp'], $this->uri, $matches))
                {
                    #Clansuite_Debug::firebug($matches);

                    # parameters found by regular expression have priority
                    if(true === isset($matches['controller']))
                    {
                        Clansuite_TargetRoute::setController($matches['controller']);
                    }

                    if(true === isset($matches['subcontroller']))
                    {
                        Clansuite_TargetRoute::setSubController($matches['subcontroller']);
                    }

                    if(true === isset($matches['action']))
                    {
                        Clansuite_TargetRoute::setAction($matches['action']);
                    }

                    if(true === isset($matches['id']))
                    {
                        Clansuite_TargetRoute::setId($matches['id']);
                    }
                }

                if(Clansuite_TargetRoute::dispatchable() === true)
                {
                    # route found
                    break;
                }
                else
                {
                    Clansuite_TargetRoute::reset();
                }
            }
        }

        #Clansuite_TargetRoute::setController($found_route);
        #Clansuite_TargetRoute::setAction('show');

        return Clansuite_TargetRoute::getInstance();
        # Clansuite_CMS::triggerEvent('onAfterInitializeRoutes', $this);
    }

    /**
     * Check if ModRewrite is activated in config
     *
     * @return bool True, if "config['routing']['mod_rewrite']" true. False otherwise.
     */
    public function isRewriteEngineOn()
    {
        if(true === isset($this->config['routing']['mod_rewrite']) and true === (bool) $this->config['routing']['mod_rewrite'])
        {
            define('REWRITE_ENGINE_ON', true);
            return true;
        }
        else # Apache Mod_Rewrite not available
        {
            define('REWRITE_ENGINE_ON', false);
            return false;
        }
    }

    /**
     * Checks if Apache Module "mod_rewrite" is loaded/enabled
     * and Rewrite Engine is enabled in .htaccess"
     * 
     * @return boolean True, if mod_rewrite on.
     */
    public function checkEnvForModRewrite()
    {
        # ensure apache has module mod_rewrite active
        if(true === function_exists('apache_get_modules') and
           true === in_array('mod_rewrite', apache_get_modules()))
         {
            if(true === is_file(ROOT . '.htaccess'))
            {
                # load htaccess and check if RewriteEngine is enabled
                $htaccess_content = file_get_contents(ROOT . '.htaccess');
                $rewriteEngineOn = preg_match('/.*[^#][\t ]+RewriteEngine[\t ]+On/i', $htaccess_content);

                if(true === (bool) $rewriteEngineOn)
                {
                    return true;
                }
                else
                {
                    # @todo Hint: Please enable mod_rewrite in htaccess.
                    return false;
                }
            }
            else
            {
                # @todo Hint: No htaccess file found. Create and enable mod_rewrite.
                return false;
            }
        }
        else
        {
            # @todo Hint: Please enable mod_rewrite module for Apache.
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
        #Clansuite_Debug::firebug('The unprepared Server Request URI is "' . $request_uri . '"');

        # add slash in front + remove slash at the end
        $this->uri = '/' . trim($request_uri, '/');

        # path subtraction (get length of dirname of php_self and subtract from uri)
        $url_directory_prefix_length = strlen(dirname($_SERVER['PHP_SELF']));
        $this->uri = substr($this->uri, $url_directory_prefix_length);

        #Clansuite_Debug::firebug('The prepared Server Request URI is "' . $this->uri . '"');

        return $this->uri;
    }

    /**
     * NoRewriteRouting
     *
     * URL Examples
     * a) index.php?mod=news=action=archive
     * b) index.php?mod=news&sub=admin&action=edit&id=77
     *
     * mod      => controller => clansuite_module_mod (".module.php")
     * sub      => file type  => ".admin.php"
     * action   => method     => action_method
     * *id*     => additional call params for the method
     */
    public function NoRewriteRoute()
    {
        # Controller
        if(true === isset($this->uri_segments['mod']))
        {
            Clansuite_TargetRoute::setController($this->uri_segments['mod']);
            unset($this->uri_segments['mod']);
        }

        # SubController
        if(true === isset($this->uri_segments['sub']))
        {
            Clansuite_TargetRoute::setSubController($this->uri_segments['sub']);
            unset($this->uri_segments['sub']);
        }

        # Action
        if(true === isset($this->uri_segments['action']))
        {
            Clansuite_TargetRoute::setAction($this->uri_segments['action']);
            unset($this->uri_segments['action']);
        }

        # Parameters
        if(count($this->uri_segments) > 0)
        {
            Clansuite_TargetRoute::setParameters($this->uri_segments);
            unset($this->uri_segments);
        }

        if(Clansuite_TargetRoute::dispatchable() === true)
        {
            return Clansuite_TargetRoute::getInstance();
        }
    }

    /**
     * URLParser for NoRewrite URL/URIs
     *
     * This URLParser has to extract mod, sub, action, id/parameters from the URI.
     * This is the Standard_Request_Resolver.
     *
     * @param string $url The Request URL
     */
    private function UrlParser_NoRewrite($uri)
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
         * This addresses the pair relationship between parameter name and value, like "id=77".
         */
        $parameters = array();
        $key = '';
        $value = '';
        $query_pair = '';

        if(count($uri_query_array) > 0)
        {
            foreach($uri_query_array as $query_pair)
            {
                if( false !== strpos($query_pair, '='))
                {
                    list($key, $value) = explode('=', $query_pair);
                    $parameters[$key] = $value;
                }
            }
        }
        unset($uri_query_string, $uri_query_array, $query_pair, $key, $value);

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
    private function UrlParser_Rewrite($uri)
    {
        /**
         * The query string up to the question mark (?)
         *
         * Removes everything after a "?".
         * Note: with correct rewrite rules in htaccess, this conditon is not needed.
         */
        $pos = mb_strpos($uri, '?');
        if($pos !== false)
        {
            $uri = mb_substr($uri, 0, $pos);
        }

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
            $uri_dot_array = explode('.', $uri);
            # chop off the last piece as the extension
            self::$extension = array_pop($uri_dot_array);
            # there might be multiple dots in the url
            # thats why implode is used to reassemble the segmentized array to a string again
            # but note the different glue string: the dots are now replaced by slashes ,)
            # = ini_get('arg_separator.output')
            $uri = implode('/', $uri_dot_array);
            unset($uri_dot_array);
        }
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
        unset($url_split);
    }

    /**
     * Replaces the placeholders in a route, like alpha, num, word
     * with their regular expressions for later preg_matching.
     * This is used while adding a new Route.
     *
     * @param string $route_with_placeholder A Route with a placeholder like alpha or num.
     */
    public static function placeholdersToRegexp($route_with_placeholders)
    {
        $placeholders = array('(:id)', '(:num)', '(:alpha)', '(:alphanum)', '(:any)', '(:word)',
                              '(:year)', '(:month)', '(:day)');

        $replacements = array('([0-9]+)', '([0-9]+)', '([a-zA-Z]+)', '([a-zA-Z0-9]+)', '(.*)', '(\w+)',
                              '([12][0-9]{3})', '(0[1-9]|1[012])', '(0[1-9]|1[012])');

        return str_replace($placeholders, $replacements, $route_with_placeholders);
    }

    /**
     * This unsets all Routes of Routing Table ($this->routes)
     * which have more segments then the request uri.
     */
    public function removeRoutesBySegmentCount()
    {
        $route_pattern = '';
        $route_values = '';

        foreach($this->routes as $route_pattern => $route_values)
        {
            if($route_values['number_of_segments'] === count($this->uri_segments))
            {
                continue;
            }
            else
            {
                unset($this->routes[$route_pattern]);
            }
        }
    }

    /**
     * Method checks if routes caching is activated in config,
     * maybe we can load routes from cache.
     *
     * @return bool True if route caching active.
     */
    public function checkRouteCachingActive()
    {
        if(true === isset($this->config['routing']['cache_routes']) and
           true === $this->config['routing']['cache_routes'])
        {
            self::$use_cache = true;
        }
        else
        {
            self::$use_cache = false;
        }
    }

    /**
     * Register the default routes.
     */
    public function loadDefaultRoutes()
    {
        $this->checkRouteCachingActive();

        # Load Routes from Cache
        if(true === self::$use_cache and empty($this->routes) and Clansuite_Cache::contains('clansuite.routes'))
        {
            $this->addRoutes(Clansuite_Cache::read('clansuite.routes'));
        }

        # Load Routes from routes.config.php
        if(empty($this->routes))
        {
            $this->addRoutes(Clansuite_Routes_Manager::loadRoutesFromConfig());

            # and save these routes to cache
            if(true === self::$use_cache)
            {
                Clansuite_Cache::store('clansuite.routes', $this->getRoutes());
            }
        }

        /**
         * Connect some default fallback Routes
         *
         * With ArrayAccess: $r['/:controller'];
         */
        if(true === empty($this->routes))
        {
            $this->addRoute('/:controller');
            $this->addRoute('/:controller/:action');
            $this->addRoute('/:controller/:action/:id');
            $this->addRoute('/:controller/:action/:id/:format');

            $this->addRoute('/:controller/:subcontroller');
            $this->addRoute('/:controller/:subcontroller/:action');
            $this->addRoute('/:controller/:subcontroller/:action/:id');
            $this->addRoute('/:controller/:subcontroller/:action/:id/:format');
        }
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
            return null;
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
    function addRoute($url_pattern, array $route_options = null);
    function addRoutes(array $routes);
    function getRoutes();
    function delRoute($name);
    function generateURL($url_pattern, array $params = null, $absolute = false);
    function route();
}
?>