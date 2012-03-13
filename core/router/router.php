<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Router;

use Koch\MVC\HttpRequestInterface;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Router
 *
 * Koch_Router does URL Formatting and internal Rewriting.
 * The URL is segmented and restructured to fit the internal route to a controller.
 * The internal routes are described in a central routing configuration file.
 * This central config is updated on installation and deinstallation of modules and plugins.
 * @see Koch_Routes_Manager
 *
 * Normally all requests made map to a specific physical resource rather than a logical name.
 * With Routing you are able to map a logical name to a specific physical name.
 * Examples: map a logical URL (a mod_rewritten one) to a Controller/Method/Parameter
 * or map a FileRequest via logical URL (a mod_rewritten one) to a DownloadController/Method/Parameters.
 * Routes are a valuable concept because they separate your URLs from your data.
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Router
 */
class Router implements RouterInterface, \ArrayAccess
{
    /**
     * @var object Koch\Config
     */
    private $config;

    /**
     * Whether to use caching for routes or not.
     *
     * @var boolean
     */
    private static $use_cache = false;

    /**
     * The Request URI (came in from the HttpRequest object)
     *
     * @var string
     */
    private $uri = '';

    /**
     * The Request URI as an array.
     *
     * @var array
     */
    public $uri_segments = array();

    /**
     * The "extension" on the URI
     * Would be "html" for the URI "/news/show/1.html".
     *
     * @var string
     */
    private static $extension = '';

    /**
     * Routes Mapping Table.
     * Is an array containing several route definitions.
     *
     * @var array Routes Array
     */
    private $routes = array();

    /**
     * Constructor.
     */
    public function __construct(HttpRequestInterface $request)
    {
        $this->request = $request;

        # Set config object to the router for later access to config variables.
        $this->config = \Clansuite\CMS::getClansuiteConfig();

        # get URI from request, clean it and set it as a class property
        $this->setRequestURI(self::prepareRequestURI($request->getRequestURI()));
    }

    public function setRequestURI($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Get and prepare the SERVER_URL/URI
     *
     * Several fixes are applied to the $request_uri.
     *
     * When incomming via Koch_HttpRequest::getRequestURI()
     * the $request_rui is already
     * (1) lowercased and
     * (2) urldecoded.
     *
     * This function
     * (3) strips slashes from the beginning and the end,
     * (4) prepends a slash and
     * (5) strips PHP_SELF from the uri string.
     *
     * A multislash removal is not needed, because of the later usage of preg_split().
     *
     * @param string $request_url Koch_HttpRequest::getRequestURI
     * @return string Request URL
     */
    public function prepareRequestURI($request_uri)
    {
        # add slash in front + remove slash at the end
        $this->uri = '/' . trim($request_uri, '/');

        # subtract PHP_SELF from uri
        if(defined('REWRITE_ENGINE_ON') and !REWRITE_ENGINE_ON)
        {
            $url_directory_prefix_length = strlen(dirname($_SERVER['PHP_SELF']));
            $this->uri = substr($this->uri, $url_directory_prefix_length);
        }

        return $this->uri;
    }

    /**
     * Adds a route.
     *
     * @param string $url_pattern A route string.
     * @param array $requirements Routing options.
     */
    public function addRoute($url_pattern, array $requirements = null)
    {
        /**
         * 1) Preprocess the route
         */

        $url_pattern = ltrim($url_pattern, '/');

        /**
         * Replace all static placeholders, like (:num) or (:id)
         * with their equivalent regular expression ([0-9]+).
         *
         * All static placeholders not having a regexp equivalent,
         * will remain on the route, like ":news".
         * They will be handled as "static named" routes and route directly to
         * a controller with the same name!
         * @todo: use another prefix for the static named routes, maybe "!news"
         */
        if (strpos($url_pattern, '(') !== false)
        {
            $url_pattern = self::placeholdersToRegexp($url_pattern);
        }

        # explode the uri pattern to get uri segments
        $segments = explode('/', $url_pattern);

        # combines all regexp patterns of segements to one regexp pattern for the route
        $regexp = $this->processSegmentsRegExp($segments, $requirements);

        $options = array(
            'regexp' => $regexp,
            'number_of_segments' => count($segments),
            'requirements' => $requirements
        );

        /**
         * 2) Finally add the *now preprocessed* Route.
         */
        $this->routes['/'.$url_pattern] = $options;
    }

    /**
     * Returns a regexp pattern for the route
     * by combining the regexp patterns of all uri segments.
     *
     * It's basically string concatenation of regexp strings.
     *
     * @param array $segments Array with URI segments.
     * @param array $requirements Array with
     * @return string Regular Expression for the route.
     */
    public function processSegmentsRegExp(array $segments, array $requirements = null)
    {
        # start regular expression
        $regexp = '#';

        # process all segments
        foreach($segments as $segment)
        {

            /**
             * process static named parameters, like ":contoller"
             *
             * The name has to start with a ":".
             * Then this is a name of an index variable.
             */
            if(strpos($segment, ':') !== false)
            {
                $name = substr($segment, 1); # remove :

                # is there a requirement for this param?
                if(true === isset($requirements[$name]))
                {
                    # add it to the regex
                    $regexp .= '(?P<' . $name . '>' . $requirements[$name] . ')';
                    # and remove the requirement
                    unset($requirements[$name]);
                }
                else # no requirement
                {
                    $regexp .= '(?P<' . $name . '>[a-z0-9_-]+)';
                }
            }
            else
            {
                # process static parameter = string => "/index" or "/news"
                $regexp .= '\\/' . $segment;
            }

            # regexp between segments
            $regexp .= '\/?';
        }

        # finish regular expression
        $regexp .= '#';

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
     * Resets the routes array.
     *
     * @return object Koch_Router
     */
    public function reset($load_default_routes = false)
    {
        $this->routes = array();
        TargetRoute::reset();

        if($load_default_routes === true)
        {
            $this->loadDefaultRoutes();
        }

        return $this;
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
     * @param $encode bool True (default) encodes the "&" in the url (amp).
     */
    public static function buildURL($urlstring, $encode = true, $force_modrewrite_on = true)
    {
        # return, if urlstring is already a qualified url (http://...)
        if(false !== strpos($urlstring, WWW_ROOT . 'index.php?'))
        {
            return $urlstring;
        }

        # only the http prefix is missing
        if(false !== strpos($urlstring, 'index.php?'))
        {
            return WWW_ROOT . $urlstring;
        }

        # cleanup: remove all double slashes
        while (false !== strpos($urlstring, '//'))
        {
            $urlstring = str_replace('//', '/', $urlstring);
        }

        # cleanup: remove space and slashes from begin and end of string
        $urlstring = trim($urlstring, ' /');

        /**
         * mod_rewirte is on. the requested url style is:
         * ROOT/news/2
         */
        if(REWRITE_ENGINE_ON == true and $force_modrewrite_on === true)
        {
           return WWW_ROOT . ltrim($urlstring, '/');
        }
        /**
         * mod_rewrite is off. the requested url style is:
         * ROOT/index.php?mod=new&action=show&id=2
         */
        else
        {
            # get only the part after "index.php?"
            if(false !== strpos($urlstring, 'index.php?'))
            {
                $urlstring = strstr($urlstring, 'index.php?');
            }

            # $urlstring contains something like "/news/show/2"
            # explode the string into an indexed array
            $url_parameters = explode('/', $urlstring);

            /**
             * This turns the indexed url parameters array into a named one.
             * [0]=> "news"  to  [mod]    => "news"
             * [1]=> "show"  to  [action] => "show"
             * [2]=> "2"     to  [id]     => "2"
             *
             * It also a static whitelist for url parameter keys.
             *
             * @todo how do i get the dynamic parameter names in here? year, date, etc.
             * To solve this, maybe, the first index might be used to load the routes of that module.
             * Then a reverse lookup in the routes table. For now this is static.
             */
            if(isset($url_parameters[1]) and $url_parameters[1] === 'admin')
            {
                # module admin whitelist
                $url_keys = array('mod', 'sub', 'action', 'id', 'type');
            }
            else
            {
                # public module whitelist
                $url_keys = array('mod', 'action', 'id', 'type');
            }

            $url_data = Koch_Functions::array_unequal_combine($url_keys, $url_parameters);

            /**
             * determine the separator.
             * it defaults to "&amp;" for internal usage in html documents
             */
            $arg_separator = ($encode === true) ? '&amp;' : '&';

            /**
             * Finally: build and return the url!
             */
            return WWW_ROOT . 'index.php?' . http_build_query($url_data, '', $arg_separator);
        }
    }

    /**
     * Main method of Koch_Router
     *
     * The routing workflow is
     * 1. firstly, check if ModRewrite is enabled,
     *    this decides upon which URL parser to use.
     * 2. URL parser splits the uri into uri segments.
     * 3. routes are initalized (the defaultRoute and all module routes)
     * 4. try to find a route/map matching with the uri_segments
     * 5. if no mapping applies, then set default values from config and fallback to a static routing
     * 6. always! -> found_route -> call!
     */
    public function route()
    {
        /**
         * If there are no uri segments, loading routes and matching is pointless.
         *
         * Dispatch to the default route, which is defined in configuration.
         */
        if(empty($this->uri) or $this->uri === '/')
        {
            TargetRoute::setController($this->config['defaults']['module']);
            TargetRoute::setAction($this->config['defaults']['action']);

            if(TargetRoute::dispatchable() === true)
            {
                return TargetRoute::getInstance();
            }
        }

        # attach more routes to this object via the event "onInitializeRoutes"
        #Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        # initalize Routes
        $this->loadDefaultRoutes();

        # map match uri
        return $this->match();
    }

    /**
     * Matches the URI against the Routes Mapping Table
     * taking static, dynamic and regexp routings into account.
     *
     * In other words, it "map matches the URI".
     *
     * @return object TargetRoute
     */
    public function match()
    {
        # do we have some routes now?
        if(0 === count($this->getRoutes()))
        {
            throw new \OutOfBoundsException(_('The routes lookup table is empty. Define some routes.'));
        }

        # get URI from request, clean it and set it as a class property
        $this->setRequestURI(self::prepareRequestURI($this->uri));

        /**
         * Detects if Mod_Rewrite engine is active and
         * calls the proper URL Parser method for the extraction of uri segments.
         */
        if(true === $this->isRewriteEngineOn() and empty($_GET['mod']) and empty($_GET['sub']))
        {
            $this->uri_segments = $this->parseUrl_Rewrite($this->uri);
        }
        else
        {
            $this->uri_segments = $this->parseUrl_noRewrite($this->uri);
        }

        /**
         * Reduce the map lookup table, by dropping all routes
         * with more segments than the current requested uri.
         */
        if(count($this->routes) > 1)
        {
            self::removeRoutesBySegmentCount();
        }

        /**
         * Do we have a direct match ?
         *
         * Example:
         * The request URI "/news/index" relates 1:1 to $routes['/news/index'].
         */
        if(isset($this->routes[$this->uri]) === true)
        {
            $found_route = $this->routes[$this->uri];
            return $this->setSegmentsToTargetRoute($found_route);
        }
        else
        {
            /**
             * No, there wasn't a 1:1 match.
             * Now we have to check the uri segments.
             *
             * So we loop over the remaining routes and try to map match the uri_segments.
             */
            foreach($this->routes as $route_pattern => $route_values)
            {
                unset($route_pattern);

                $matches = '';

                /**
                 * Process:     Dynamic Regular Expression Parameters
                 *
                 * Example:
                 * URI: /news
                 * Rule /:controller
                 * Regexp: "#(?P<controller>[a-z0-9_-]+)\/?#"
                 * Matches: $matches['controller'] = 'news';
                 */
                if(preg_match( $route_values['regexp'], $this->uri, $matches))
                {
                    foreach($matches as $key => $value)
                    {
                        if(is_numeric($key))
                        {
                            unset($matches[$key]);
                        }
                    }
                    $this->setSegmentsToTargetRoute($matches);
                }

                #TargetRoute::_debug();

                if(TargetRoute::dispatchable() === true)
                {
                    # route found
                    break;
                }
                else
                {
                    TargetRoute::reset();
                }
            }
        }



        /**
         * Finally: fetch our Target Route Object.
         */
        $targetRoute = TargetRoute::getInstance();

        /**
         * Inject the target route object back to the request.
         * Thereby the request gains full knowledge about the
         * URL mapping (external to internal). We might ask
         * the request later, where the requests maps to.
         */
        $this->request->setRoute($targetRoute);

        return $targetRoute;
        # Clansuite_CMS::triggerEvent('onAfterInitializeRoutes', $this);
    }

    /**
     * Parses the URI and returns an array with URI segments.
     *
     * URL Parser for Apache Mod_Rewrite URL/URIs.
     * Think of it as a ModRewrite_Request_Resolver.
     *
     * This is based on htaccess rewriting with [QSA,L].
     * QSA = Query Append String.
     *
     * Maybe rewriting with [E] would allow a much faster url parsing by string splitting,
     * because of a fixed url style (think of @ or ___ as separators).
     * @todo consider using rewriting with [E] in htaccess and string splitting
     *
     * @param string $url The Request URL
     * @return array Array with URI segments.
     */
    private static function parseUrl_Rewrite($uri)
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
        $uri_segments = preg_split('#/#', $uri, -1, PREG_SPLIT_NO_EMPTY);
        unset($uri);

        /**
         * Finished!
         */
        return $uri_segments;
    }

    /**
     * Parses the URI and returns an array with URI segments.
     *
     * URL Parser for NoRewrite URL/URIs.
     * This URLParser has to extract mod, sub, action, id/parameters from the URI.
     * Alternate name: Standard_Request_Resolver.
     *
     * @param string $url The Request URL
     * @return array Array with URI segments.
     */
    private function parseUrl_noRewrite($uri)
    {
        if(false !== strpos('?', $uri))
        {
            return array(0 => $uri);
        }

        # use some parse_url magic to get the url_query part from the uri
        $uri_query_string = parse_url($uri, PHP_URL_QUERY);
        unset($uri);

        /**
         * The ampersand (&)
         *
         * Use ampersand as the split char for string to array conversion.
         */
        $uri_query_array = explode('&', $uri_query_string);

        /**
         * The equals sign (=)
         *
         * This addresses the pair relationship between parameter name and value, like "id=77".
         */
        $uri_segments = array();
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
                    $uri_segments[$key] = $value;
                }
            }
        }
        unset($uri_query_string, $uri_query_array, $query_pair, $key, $value);

        /**
         * Finished!
         */
        return $uri_segments;
    }

    /**
     * Check if Apache mod_rewrite is activated in configuration.
     *
     * @return bool True, if "config['routing']['mod_rewrite']" true. False otherwise.
     */
    public function isRewriteEngineOn()
    {
        if(defined('REWRITE_ENGINE_ON'))
        {
            return true;
        }

        if(true === isset($this->config['routing']['mod_rewrite']) and
           true === (bool) $this->config['routing']['mod_rewrite'])
        {
            define('REWRITE_ENGINE_ON', true);
            return true;
        }
        else
        {
            # Apache Mod_Rewrite not available
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
        if( true === function_exists('apache_get_modules')
        and true === in_array('mod_rewrite', apache_get_modules()))
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
     * setSegmentsToTargetRoute
     *
     * This takes the requirements array or the uri_segments array
     * and sets the proper parameters on the Target Route,
     * thereby making it dispatchable.
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
    public function setSegmentsToTargetRoute($array)
    {
        # if array is an found route, the values are in the requirements subarray
        if(array_key_exists('requirements', $array))
        {
            $array = $array['requirements'];
        }

        # Controller
        if(true === isset($array['mod']))
        {
            TargetRoute::setController($array['mod']);
            unset($array['mod']);
        }
        if(true === isset($array['controller']))
        {
            TargetRoute::setController($array['controller']);
            unset($array['controller']);
        }
        # SubController
        if(true === isset($array['sub']))
        {
            TargetRoute::setSubController($array['sub']);
            unset($array['sub']);
        }

        if(true === isset($array['subcontroller']))
        {
            TargetRoute::setSubController($array['subcontroller']);
            unset($array['subcontroller']);
        }

        # action
        if(true === isset($array['action']))
        {
            TargetRoute::setAction($array['action']);
            unset($array['action']);
        }


        # Parameters
        if(count($array) > 0)
        {
            TargetRoute::setParameters($array);
            unset($array);
        }

        return TargetRoute::getInstance();
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

        unset($route_pattern, $route_values);
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
        if(true === self::$use_cache and empty($this->routes) and \Koch\Cache::contains('clansuite.routes'))
        {
            $this->addRoutes(\Koch\Cache::read('clansuite.routes'));
        }

        # Load Routes from Config "routes.config.php"
        if(empty($this->routes))
        {
            $this->addRoutes(Manager::loadRoutesFromConfig());

            # and save these routes to cache
            if(true === self::$use_cache)
            {
                Koch\Cache::store('clansuite.routes', $this->getRoutes());
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
            $this->addRoute('/:controller/:action');                $this->addRoute('/:controller/(:id)');
            $this->addRoute('/:controller/:action/(:id)');            $this->addRoute('/:controller/(:id)/:action');
            $this->addRoute('/:controller/:action/(:id)/:format');

            $this->addRoute('/:controller/:subcontroller');
            $this->addRoute('/:controller/:subcontroller/:action');
            $this->addRoute('/:controller/:subcontroller/:action/(:id)');
            $this->addRoute('/:controller/:subcontroller/:action/(:id)/:format');
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
?>