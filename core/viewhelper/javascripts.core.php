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
 * Clansuite_Javascripts is a (View) Helper Library and Service Gateway for all
 * the Javascript and CSS Libraries utilized.
 *
 * Purpose: Standardization of Initialization and Loading of single Files and Libraries
 *          Provide easier Access to often spread resources
 *
 * We have the following directories:
 * For single (non-library) files: "/themes/core/css" and "/themes/core/javascripts/".
 * For libraries with own subfolders for css, js, misc stuff: "/themes/core/libraries/".
 */
class Clansuite_Javascripts extends Clansuite_Layout
{

    public static function addJS_JQuery()
    {
        self::addJS('jquery/jquery.js');
        self::addJS('jquery/jquery.ui.js');
    }

    public static function addJS_Lightbox()
    {
        self::addJS('lightbox/scripts/lightbox.js');
    }

    public static function addCSS_Lightbox()
    {
        self::addCSS('lightbox/css/lightbox.css');
    }

    public static function addJS_Moofx()
    {
        self::addJS('moo-fx/moo.fx.js');
    }

    public static function addJS_Mootools()
    {
        self::addJS('mootools/mootools.js');
        self::addJS('mootools/mootools-more.js');
    }

    public static function addJS_Overlib()
    {
        self::addJS('overlib/overlib.js');
    }

    public static function addJS_Tabpane()
    {
        self::addJS('tabpane/tabpane.js');
    }

    public static function addJS_Clip()
    {
        self::addJS('clip');
    }

    /**
     * Adds a JQuery Link, which fetches directly from jquery.com
     *
     * If you don't specifiy the version parameter, the latest version will be fetched.
     * This procedure is implemented to provide an easy of developing with the latest release of JQuery.
     * It has several problems:
     * 1) incompatibilities of the latest version to dependent local javascripts
     * 2) the download depends on jquery.com and not on your own domain
     * 3) this adds one external reference to the page loading and requires a DNS-lookup
     * 4) if you use clansuite as intranet system or offline, it's simply not loadable
     *
     * The best practice usage is to provide a version number.
     * The versions are whitelisted to keep a certain compatibilty frame.
     *
     * @param $version string The JQuery Version Number to load, like "1.3.2".
     * @param $service string jquery for download from code.jquery.com, google for google.com/jsapi
     */
    public static function addJS_JQuery_Service($version = null, $service = 'google')
    {
        # determine service
        if($service == 'jquery')
        {
            # load from jquery.com
            if($version === null)
            {
                self::addJS('http://code.jquery.com/jquery-latest.pack.js');
            }
            else
            {
                /**
                 * JQuery version whitelist ensures a certain compatibilty frame
                 */
                $jquery_version_whitelist = array( '1.4.2', '1.4.1' ); # not 'latest'

                if( in_array($version, $jquery_version_whitelist) )
                {
                    self::addJS('http://code.jquery.com/jquery-'.$version.'.pack.js');
                }
            }
        }
        else
        {
            # load from google.com
            self::addJS_JQuery_GoogleCDN_Service($version);
        }
    }

    /**
     * Adds a JQuery Link, which fetches directly from google.com CDN
     *
     * If you don't specifiy the version parameter, the latest version will be fetched.
     * For problems with this approach, @see addJS_JQuery_Service.
     *
     * The best practice usage is to provide a version number.
     * The versions are whitelisted to keep a certain compatibilty frame.
     *
     * @param $version string The GoogleCDN Version Number to load, like "1.3.2".
     */
    public static function addJS_JQuery_GoogleCDN_Service($version = null)
    {
        if($version === null)
        {
            $version = 'latest';
        }
        else
        {
            /**
             * JQuery version whitelist ensures a certain compatibilty frame
             */
            $jquery_version_whitelist = array( '1.4.2', '1.4.1' ); # not 'latest'

            if( in_array($version, $jquery_version_whitelist) )
            {
                $this->jquery_initscript  = '';
                $this->jquery_initscript .= "    <script src=\"http://www.google.com/jsapi\"></script>\n";
                $this->jquery_initscript .= "    <script>\n";
                $this->jquery_initscript .= "      google.load('jquery', '{$version}');\n";
                $this->jquery_initscript .= "      var $j = jQuery.noConflict();;\n";
                $this->jquery_initscript .= "    </script>\n";

                return $this->jquery_initscript;
            }
        }
    }

    /** Wrapper Methods **/

    /**
     * addMultipleJS - Wrapper Method
     *
     * @params array  array with several filenames and their paths
     * $filenames['path','filename']
     */
    public static function addMultipleJS($filenames)
    {
        if(is_array($filenames))
        {
            foreach($filenames as $filename)
            {
                return '<script src="{$www_root_themes_core}'.$filename.'" type="text/javascript"></script>'.CR;
            }
        }
    }

    /**
     * addJS - Wrapper Method
     *
     * @params string javascript filename to load
     */
    public static function addJS($filename)
    {
        $javascript = '{$www_root_themes_core}/javascript/'.$filename.'.js';

        if( defined('OB_GZIP') )
        {
            #self::addToCompressionWhitelist($javascript);
            return '<script  src="{$www_root_themes_core}/compress.php?js='.$javascript.'" type="text/javascript"></script>'.CR;
        }
        else
        {
            return '<script src="'.$javascript.'" type="text/javascript"></script>'.CR;
        }
    }

    /**
     * addJSInit - Wrapper Method
     *
     * @params string name of the javascript to initialize
     * @params string init-string to initialize the js
     */
    public static function addJSInit($name, $init)
    {
        $addJSInit  = '';
        $addJSInit .= '<!-- initialize javascript: '.$name.' -->'.CR;
        $addJSInit .= '<script type="text/javascript">'.CR.'// <![CDATA[';
        $addJSInit .= $init;
        $addJSInit .= CR.'// ]]></script>'.CR;

        return $addJSInit;
    }

    /**
     * addCSS - Wrapper Method
     *
     * @params string filename of the cascading style sheet to load
     * @params boolean display the iehack css in case true, default is false
     */
    public static function addCSS($filename, $iehack = false)
    {
        if($iehack == true)
        {
            return '<!--[if IE]>';
        }

        return "<style type=\"text/css\"> @import \"{$www_root_themes_core}/css/{$filename}.css\"; </style>".CR;

        if($iehack == true)
        {
            return '<![endif]-->';
        }
    }
}
?>