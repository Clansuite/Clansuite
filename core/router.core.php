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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite_Route
 *
 * Purpose: Clansuite_Route does URL Formating and internal Rewriting.
 * The URL is segmented and restructured to fit the internal route to a controller.
 * The internal routes are described in a central routing configuration file (whitelist).
 * On Installation the routes are added (add_url_route), on deinstallation removed (del_url_route).
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 */
class Clansuite_Router
{
    # Route
    private $route;

    # URI Segments
    private $module;
    private $sub;
    private $action;
    private $id;

    # Objects: Request, Config
    private $request;
    private $config;
    
    # Base URL
    private static $baseURL = 'index.php/';
    
    public function construct(Clansuite_HttpRequest $request, Clansuite_Config $config)
    {
        $this->request = $request;
        $this->config  = $config;
    }
    
    /**
     * Returns the current Route
     *
     * @return
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * getBaseURL() returns the web path of the application
     * convenience method for Clansuite_HttpRequest::getBaseURL()
     *
     * @returns string the web path of the application (WWW_ROOT + baseURL)
     */
    public static function getBaseURL()
    {
        #return WWW_ROOT . '/' . self::$baseURL . '?';
        return Clansuite_HttpRequest::getBaseURL();
    }

    /**
     * Adds and retruns an url-string segment (&x=y) to the baseurl
     * If Package pecl_http is present it is used.
     *
     * @param string $appendString the URL parameter string to append to the url
     * @param string $url the url to append to
     * @example
     *   $sUrl = $this->addQueryToUrl("par1=value1&par2=value2...");
     */
    public static function addQueryToUrl($appendString, $url = null)
    {   
        #Clansuite_Xdebug::firebug( http_build_url(self::$baseURL, array( "query"  => $appendString  ), HTTP_URL_JOIN_QUERY ) );
        if (extension_loaded('http'))
        {    
            # add additional query parameter to the url
            return http_build_url(self::$baseURL, array( "query"  => $appendString  ), HTTP_URL_JOIN_QUERY );
        }
        else
        {
            if(is_null($url))
            {
                $url = self::$baseURL;
            }

            if( (is_int(strpos($url, "?"))) )
            {
                $url = $url . "&" . $appendString;
            }
            else
            {
                $url = $url . "?" . $appendString;
            }
            
            return $url;
        }
    }

    /**
     * Add an path-string segment to the baseurl or exchange the path
     * Note: If Package pecl_http is present it is used.
     *
     * @param string $path The path to append to an existing path or the new path.
     * @param boolean $exchange If exchange is true, the path is exchanged. Otherwise the path is appended.
     */
    public static function addPathToUrl($path, $exchange)
    {   
        if (extension_loaded('http'))
        {
            if($exchange === true)
            {
                return http_build_url(self::$baseURL, array( "path"  => $path  ));
            }
            else # append path
            {
                return http_build_url(self::$baseURL, array( "path"  => $path ), HTTP_URL_JOIN_PATH );
            }
        }
        else
        {
            # @todo
        }
    }
}
?>