<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Router;

use Koch\Http\HttpRequestInterface;

/**
 * Router
 *
 * Router does URL Formatting and internal Rewriting.
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

        // Set config object to the router for later access to config variables.
        $this->config = \Clansuite\Application::getClansuiteConfig();

        // get URI from request, clean it and set it as a class property
        $this->uri = self::prepareRequestURI($request->getRequestURI());

        //$this->abspath   = dirname($_SERVER['SCRIPT_FILENAME'] . '/');
        //$this->url       = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->abspath);
        //$this->fragments = explode('/', $_SERVER['REQUEST_URI']);
        //$this->domain    = explode('.', $_SERVER['SERVER_NAME']);
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
     * @param  string $request_url Koch_HttpRequest::getRequestURI
     * @return string Request URL
     */
    public function prepareRequestURI($uri)
    {
        // if XDebug on, remove "xdebug_session_start=xdebug" from routing process
        if (function_exists('xdebug_time_index') === true) {
            $uri = str_replace('?xdebug_session_start=xdebug', '', $uri);
        }

        // subtract PHP_SELF from uri
        if (defined('REWRITE_ENGINE_ON') and REWRITE_ENGINE_ON == false) {
            $url_directory_prefix_length = strlen(dirname($_SERVER['PHP_SELF']));
            $uri = substr($uri, $url_directory_prefix_length);
        }

        // add slash in front + remove slash at the end
        if ($uri !== "/") {
            $uri = '/' . trim($uri, '/');
        }

        $this->uri = $uri;

        return $uri;
    }

    /**
     * Adds a route.
     *
     * @param string $url_pattern  A route string.
     * @param array  $requirements Routing options.
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
         */
        if (strpos($url_pattern, '(') !== false) {
            $url_pattern = self::placeholdersToRegexp($url_pattern);
        }

        // explode the uri pattern to get uri segments
        $segments = explode('/', $url_pattern);

        // combines all regexp patterns of segements to one regexp pattern for the route
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
     * @param  array  $segments     Array with URI segments.
     * @param  array  $requirements Array with
     * @return string Regular Expression for the route.
     */
    public function processSegmentsRegExp(array $segments, array $requirements = null)
    {
        // start regular expression
        $regexp = '#';

        // process all segments
        foreach ($segments as $segment) {

            /**
             * process static named parameters, like ":contoller"
             *
             * The name has to start with a ":".
             * Then this is a name of an index variable.
             */
            if (strpos($segment, ':') !== false) {
                $name = substr($segment, 1); // remove :

                // is there a requirement for this param? 'id' => '([0-9])'
                if (isset($requirements[$name]) === true) {
                    // add it to the regex
                    $regexp .= '(?P<' . $name . '>' . $requirements[$name] . ')';
                    // and remove the requirement
                    unset($requirements[$name]);
                } else { // no requirement
                    $regexp .= '(?P<' . $name . '>[a-z_-]+)';
                }
            } else {
                // process static parameter = string => "/index" or "/news"
                $regexp .= '\\/' . $segment;
            }

            // regexp between segments
            $regexp .= '\/?';
        }

        // finish regular expression
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
        foreach ($routes as $route => $options) {
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

        if ($load_default_routes === true) {
            $this->loadDefaultRoutes();
        }

        return $this;
    }

    /**
     * Generates a URL by parameters.
     *
     * @param string $url_pattern The URL Pattern of the route
     * @param array  $params      An array of parameters
     * @param bool   $absolute    Whether to generate an absolute URL
     *
     * @return string The generated (relative or absolute) URL.
     */
    public function generateURL($url_pattern, array $params = null, $absolute = false)
    {
        $url = '';

        // @todo merge with buildURL + routing rules + parameters

        $url_pattern = $url_pattern;

        $params = $params;

        if ($absolute) {

        } else {

        }

        return $url;
    }

    /**
     * Builds a url string
     *
     * @param $urlstring String to build the url from (e.g. '/news/admin/show')
     * @param $encode bool True (default) encodes the "&" in the url (amp).
     */
    public static function buildURL($urlstring, $encode = true, $force_modrewrite_on = true)
    {
        // if urlstring is array, then a relation (urlstring => parameter_order) is given
        if (is_array($urlstring)) {
            $parameter_order = '';
            list($urlstring, $parameter_order) = each($urlstring);
        }

        // return, if urlstring is already a qualified url (http://...)
        if (false !== strpos($urlstring, WWW_ROOT . 'index.php?')) {
            return $urlstring;
        }

        // only the http prefix is missing
        if (false !== strpos($urlstring, 'index.php?')) {
            return WWW_ROOT . $urlstring;
        }

        // cleanup: remove all double slashes
        while (false !== strpos($urlstring, '//')) {
            $urlstring = str_replace('//', '/', $urlstring);
        }

        // cleanup: remove space and slashes from begin and end of string
        $urlstring = trim($urlstring, ' /');

        /**
         * mod_rewirte is on. the requested url style is:
         * ROOT/news/2
         */
        if (REWRITE_ENGINE_ON == true and $force_modrewrite_on === true) {
           return WWW_ROOT . ltrim($urlstring, '/');
        }
        /**
         * mod_rewrite is off. the requested url style is:
         * ROOT/index.php?mod=new&ctrl=admin&action=show&id=2
         */
        else {
            // get only the part after "index.php?"
            if (false !== strpos($urlstring, 'index.php?')) {
                $urlstring = strstr($urlstring, 'index.php?');
            }

            // $urlstring contains something like "/news/show/2"
            // explode the string into an indexed array
            $url_parameters = explode('/', $urlstring);

            // do we have a parameter_order given?
            if (isset($parameter_order)) {
                // replace parameter names with shorthands used in the url
                $search = array('module', 'controller', 'action');
                $replace = array('mod', 'ctrl', 'action');
                $parameter_order = str_replace($search, $replace, $parameter_order);

                $url_keys = explode('/', $parameter_order);
            } else {
                // default static whitelist for url parameter keys
                $url_keys = array('mod', 'ctrl', 'action', 'id', 'type');
            }

            /**
             * This turns the indexed url parameters array into a named one.
             * [0]=> "news"  to  [mod]    => "news"
             * [1]=> "show"  to  [action] => "show"
             * [2]=> "2"     to  [id]     => "2"
             */
            $url_data = \Koch\Functions\Functions::array_unequal_combine($url_keys, $url_parameters);

            // determine the separator. it defaults to "&amp;" for internal usage in html documents
            $arg_separator = ($encode === true) ? '&amp;' : '&';

            // Finally: build and return the url!
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
        if (empty($this->uri) or $this->uri === '/') {
            self::dispatchToDefaultRoute();
        }

        // attach more routes to this object via the event "onInitializeRoutes"
        #Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        // initalize Routes
        $this->loadDefaultRoutes();

        // map match uri
        return $this->match();

        // results: route is "dispatchable" or route to "404"
    }

    public static function dispatchToDefaultRoute()
    {
       return TargetRoute::getInstance();
    }

    public static function dispatchTo404()
    {
        #TargetRoute::setController('error');
        #TargetRoute::setAction('routenotfound');
        #TargetRoute::setParameters('');
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
        // do we have some routes now?
        if (0 === count($this->routes)) {
            throw new \OutOfBoundsException(_('The routes lookup table is empty. Define some routes.'));
        }

        // get URI from request, clean it and set it as a class property
        //$this->setRequestURI(self::prepareRequestURI($this->uri));

        /**
         * Detects if Mod_Rewrite engine is active and
         * calls the proper URL Parser/Segmentizer method for the extraction of uri segments.
         */
        if (true === $this->isRewriteEngineOn() and empty($_GET['mod']) and empty($_GET['ctrl'])) {
            $this->uri_segments = $this->parseUrl_Rewrite($this->uri);
        } else {
            $this->uri_segments = $this->parseUrl_noRewrite($this->uri);
        }

        /**
         * Reduce the map lookup table, by dropping all routes
         * with more segments than the current requested uri.
         */
        if (count($this->routes) > 1 and count($this->uri_segments) >= 1) {
            self::removeRoutesBySegmentCount();
        }

        /**
         * Process:     Static Route
         *
         * Do we have a direct match ?
         * This matches "static routes". Without any preg_match overhead.
         *
         * Example:
         * The request URI "/news/index" relates 1:1 to $routes['/news/index'].
         * The request URI "/login"      relates 1:1 to $routes['/login']
         */
        if (isset($this->routes[$this->uri]) === true) {

            // we have a direct match
            $found_route = $this->routes[$this->uri];

            // return the TargetRoute object
            return TargetRoute::setSegmentsToTargetRoute($found_route);

        } else {

            /**
             * No, there wasn't a 1:1 match.
             * Now we have to check the uri segments.
             *
             * Let's loop over the remaining routes and try to map match the uri_segments.
             */
            foreach ($this->routes as $route_pattern => $route_values) {

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
                if (preg_match( $route_values['regexp'], $this->uri, $matches)) {

                    // matches[0] contains $this->uri
                    unset($matches[0]);

                    // remove duplicate values
                    // e.g. [controller] = news
                    //      [1]          = news
                    $matches = array_unique($matches);

                    # @todo # fetch key and its position from $route_values['requirements']
                    if (count($route_values['requirements']) > 0) {
                        foreach ($route_values['requirements'] as $array_position => $key_name) {

                            // insert a new key
                            // with name from requirements array
                            // and value from matches array
                            // ([id] => 42)
                            $pos = $array_position+1;
                            $matches[$key_name] = $matches[$pos];

                            // remove the old not-named key ([2] => 42)
                            unset($matches[$pos]);
                        }
                    }

                    // insert $matches[<controller>] etc
                    TargetRoute::setSegmentsToTargetRoute($matches);
                }

                #TargetRoute::_debug();

                if (TargetRoute::dispatchable() === true) {
                    // route found, stop foreach
                    break;
                } else {
                    TargetRoute::reset();
                    continue;
                }
            }
        }

        /**
         * Finally: fetch our Target Route Object.
         */
        $targetRoute = TargetRoute::getInstance();

        /**
         * Inject the target route object back to the request.
         * Thereby the request gains full knowledge about the URL mapping (external to internal).
         * We might ask the request object later, where the requests maps to.
         */
        $this->request->setRoute($targetRoute);

        return $targetRoute;
        // Clansuite_CMS::triggerEvent('onAfterInitializeRoutes', $this);
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
     * @param  string $url The Request URL
     * @return array  Array with URI segments.
     */
    private static function parseUrl_Rewrite($uri)
    {
        $uri = str_replace(strtolower($_SERVER['SCRIPT_NAME']), '', $uri);

        /**
         * The query string up to the question mark (?)
         *
         * Removes everything after a "?".
         * Note: with correct rewrite rules in htaccess, this conditon is not needed.
         */
        $pos = mb_strpos($uri, '?');
        if ($pos !== false) {
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
        if ($pos !== false) {
            $uri_dot_array = array();
            // Segmentize the url into an array
            $uri_dot_array = explode('.', $uri);
            // chop off the last piece as the extension
            self::$extension = array_pop($uri_dot_array);
            // there might be multiple dots in the url
            // thats why implode is used to reassemble the segmentized array to a string again
            // but note the different glue string: the dots are now replaced by slashes ,)
            // = ini_get('arg_separator.output')
            $uri = implode('/', $uri_dot_array);
            unset($uri_dot_array);
        }
        unset($pos);

        /**
         * The slashes (/) and empty segments (double slashes)
         *
         * This segmentizes the URI by splitting at slashes.
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
     * @param  string $url The Request URL
     * @return array  Array with URI segments.
     */
    private function parseUrl_noRewrite($uri)
    {
        if (false !== strpos('?', $uri)) {
            return array(0 => $uri);
        }

        // use some parse_url magic to get the url_query part from the uri
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

        if (count($uri_query_array) > 0) {
            $key = '';
            $value = '';
            $query_pair = '';
            foreach ($uri_query_array as $query_pair) {
                if ( false !== strpos($query_pair, '=')) {
                    list($key, $value) = explode('=', $query_pair);
                    $uri_segments[$key] = $value;
                }
            }
            unset($query_pair, $key, $value);
        }
        unset($uri_query_string, $uri_query_array);

        // Finished!
        return $uri_segments;
    }

    /**
     * Check if Apache mod_rewrite is activated in configuration.
     *
     * @return bool True, if "config['routing']['mod_rewrite']" true. False otherwise.
     */
    public function isRewriteEngineOn()
    {
        if (defined('REWRITE_ENGINE_ON') and REWRITE_ENGINE_ON == true) {
            return true;
        }

        if(true === isset($this->config['routing']['mod_rewrite']) and
           true === (bool) $this->config['routing']['mod_rewrite'])
        {
            define('REWRITE_ENGINE_ON', true);

            return true;
        } else {
            // Apache Mod_Rewrite not available
            define('REWRITE_ENGINE_ON', false);

            return false;
        }
    }

    /**
     * Determine if URL was rewritten.
     */
    public function urlWasRewritten()
    {
        $realScriptName = $_SERVER['SCRIPT_NAME'];
        $virtualScriptName = reset(explode("?", $_SERVER['REQUEST_URI']));

        return !($realScriptName == $virtualScriptName);
    }

    /**
     * Checks if Apache Module "mod_rewrite" is loaded/enabled
     * and Rewrite Engine is enabled in .htaccess"
     *
     * @return boolean True, if mod_rewrite on.
     */
    public function checkEnvForModRewrite()
    {
        // ensure apache has module mod_rewrite active
        if( true === function_exists('apache_get_modules')
        and true === in_array('mod_rewrite', apache_get_modules()))
        {
            if (true === is_file(ROOT . '.htaccess')) {
                // load htaccess and check if RewriteEngine is enabled
                $htaccess_content = file_get_contents(ROOT . '.htaccess');
                $rewriteEngineOn = preg_match('/.*[^#][\t ]+RewriteEngine[\t ]+On/i', $htaccess_content);

                if (true === (bool) $rewriteEngineOn) {
                    return true;
                } else {
                    // @todo Hint: Please enable mod_rewrite in htaccess.
                    return false;
                }
            } else {
                // @todo Hint: No htaccess file found. Create and enable mod_rewrite.
                return false;
            }
        } else {
            // @todo Hint: Please enable mod_rewrite module for Apache.
            return false;
        }
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
        $number_of_uri_segements = count($this->uri_segments);

        foreach ($this->routes as $route_pattern => $route_values) {
            if ($route_values['number_of_segments'] === $number_of_uri_segements) {
                continue;
            } else {
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
        } else {
            self::$use_cache = false;
        }
    }

    /**
     * Register the default routes.
     */
    public function loadDefaultRoutes()
    {
        $this->checkRouteCachingActive();

        // Load Routes from Cache
        if (true === self::$use_cache and empty($this->routes) and \Koch\Cache::contains('clansuite.routes')) {
            $this->addRoutes(\Koch\Cache::read('clansuite.routes'));
        }

        // Load Routes from Config "routes.config.php"
        if (empty($this->routes)) {
            $this->addRoutes(Manager::loadRoutesFromConfig());

            // and save these routes to cache
            if (true === self::$use_cache) {
                Koch\Cache::store('clansuite.routes', $this->getRoutes());
            }
        }

        /**
         * Connect some default fallback Routes
         *
         * Example for Route definition with ArrayAccess: $r['/:controller'];
         */
        if (true === empty($this->routes)) {
            # one segment
            $this->addRoute('/:module');                                             // "/news"                 (list)
            # two segments
            $this->addRoute('/:module/:action');                                     // "/news/new"               (new)
            $this->addRoute('/:module/:controller');                                 // "/news/news"              (list)
            $this->addRoute('/:controller/(:id)', array(1 => 'id'));                 // "/news/31"        (show/update/delete)
            $this->addRoute('/:module/(:id)', array(1 => 'id'));                     // "/news/news/31"   (show/update/delete)
            # three segments
            $this->addRoute('/:module/:controller/:action');                         // "/news/news/new"          (new)
            $this->addRoute('/:controller/:action/(:id)', array(2 => 'id'));         // "/news/edit/42"           (edit)
            $this->addRoute('/:module/(:id)/:action', array(1 => 'id'));             // "/news/42/edit"           (edit)
            $this->addRoute('/:module/:controller/(:id)', array(2 => 'id'));         // "/news/news/31"   (show/update/delete)
            # four segments
            $this->addRoute('/:module/:controller/(:id)/:action', array(2 => 'id')); // "/news/news/31/edit"      (edit)
            $this->addRoute('/:module/:controller/:action/(:id)', array(3 => 'id')); // "/news/news/edit/31"      (edit)
            # five segments
            $this->addRoute('/:module/:controller/:action/(:id)/:format', array(4 => 'id')); // "/news/news/edit/31.html" (edit)
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
        if (array_key_exists($name, $this->routes) === true) {
            return $this->routes[$name];
        } else {
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
