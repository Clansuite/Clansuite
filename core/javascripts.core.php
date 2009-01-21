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
    * @version    SVN: $Id: clansuite.init.php 2610 2008-12-01 22:24:39Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

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
class Clansuite_Javascripts
{
    public static function addJS_InitCFE()
    {
        $init = '';
        Clansuite_Javascripts::addJSInit('Custom Form Elements',$init);
    }

    public static function addJS_CFE()
    {
        $basepath = '{$www_root_themes_core}/libraries/cfe';

        # Custom Form Elements - Base
        $javascript_files[] = array($basepath.'/base/cfe.base.js');

        # Custom Form Elements - Modules
        $cfe_modules = ('checkbox','radio','text','textarea','select',
                        'image','submit','reset','file','password','fieldset');
        foreach($cfe_modules as $cfe_module)
        {
            $javascript_files[] = array($basepath.'/modules/cfe.module.'.$cfe_module.'.js');
        }

        # Custom Form Elements - Addons
        $cfe_addons = ('toolTips','dependencies');
        foreach($cfe_addons as $cfe_addon)
        {
            $javascript_files[] = array($basepath.'/addons/cfe.module.'.$cfe_addon.'.js');
        }

        Clansuite_Javascripts::addMultipleJS($javascript_files);
    }

    public static function addCSS_CFE()
    {
        Clansuite_Javascripts::addCSS('cfe/css/cfe.css');
        Clansuite_Javascripts::addCSS('cfe/css/fixPrematureIE.css.css', true);
    }

    public static function addJS_JQuery()
    {
        Clansuite_Javascripts::addJS('jquery/jquery.js');
        Clansuite_Javascripts::addJS('jquery/jquery.ui.js');
    }

    public static function addJS_Lightbox()
    {
        Clansuite_Javascripts::addJS('lightbox/scripts/lightbox.js');
    }

    public static function addCSS_Lightbox()
    {
        Clansuite_Javascripts::addCSS('lightbox/css/lightbox.css');
    }

    public static function addJS_Moofx()
    {
        Clansuite_Javascripts::addJS('moo-fx/moo.fx.js');
    }

    public static function addJS_Mootools()
    {
        Clansuite_Javascripts::addJS('mootools/mootools.js');
        Clansuite_Javascripts::addJS('mootools/mootools-more.js');
    }

    public static function addJS_Overlib()
    {
        Clansuite_Javascripts::addJS('overlib/overlib.js');
    }

    public static function addJS_Tabpane()
    {
        Clansuite_Javascripts::addJS('tabpane/tabpane.js');
    }

    public static function addJS_Clip()
    {
        Clansuite_Javascripts::addJS('clip');
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
    		    echo '<script src="{$www_root_themes_core}'.$filename.'" type="text/javascript"></script>'.CR;
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
		    #Clansuite_Javascripts::addToCompressionWhitelist($javascript);
		    echo '<script  src="{$www_root_themes_core}/compress.php?js='.$javascript.'" type="text/javascript"></script>'.CR;
		}
		else
		{
		    echo '<script src="'.$javascript.'" type="text/javascript"></script>'.CR;
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

	    echo $addJSInit;
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
	        echo '<!--[if IE]>';
	    }

	    echo '<link rel="stylesheet" type="text/css" href=".'{$www_root_themes_core}.'/css/{$filename}.css" />'.CR;

	    if($iehack == true)
	    {
	        echo '<![endif]-->';
	    }
	}
}
?>