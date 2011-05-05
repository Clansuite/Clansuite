<?php
   /**
    * Clansuite - just an E-Sport CMS
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
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
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
 * Clansuite Tool Class for building compiled one import.css file
 *
 * e.g. Basic for the Builder ( CLI )
 *   $oBuilder = new Clansuite_Cssbuilder();
 *   $oBuilder->setBuilderInfo( $array );
 *   $oBuilder->build();
 *
 *   Optional:
 *   $oBuilder->addBrowser( 'ie', 'Internet Explorer', true, 'ie' );
 *
 *   Output messages
 *   $msg = $oBuilder->getOutputMsg();
 *
 * @author      Paul Brand <info@isp-tenerife.net>
 *
 * @category    Clansuite
 * @package     Tools
 * @subpackage  CssBuilder
 */
class Clansuite_Cssbuilder
{
    /**
     * Builder Version
     */
    protected static $generatorName = 'CSS-Builder';
    protected static $generatorVersion = '1.0.5';
    protected static $generatorVersionDate = '2011/04/29';

    /*
     * Contains the Builder informations
     *
     *  $_builderInfo[info]  contains:
     *               [generator]   = <generator name>
     *               [version]      = <generator version>
     *               [date]          = <version date>
     *
     *  $_builderInfo[core]  contains:
     *               [compile]     = true|false
     *               [import]       = true|false
     *
     *  $_builderInfo[frontend]  contains:
     *               [compile]     = true|false
     *               [path]          = <frontend path>
     *               [theme]       = <theme>
     *
     *  $_builderInfo[backend]  contains:
     *               [compile]     = true|false
     *               [path]          = <backend path>
     *               [theme]       = <theme>
     *
     *  $_builderInfo[browsers]  contains:
     *               [default]
     *                     [description]   = Standard Browser (Mozilla)
     *                     [active]          = true|false
     *                     [postfix]         =
     *               [ie]
     *                     [description]   = Internet Explorer
     *                     [active]          = true|false
     *                     [postfix]         = ie
     *               [chrome]
     *                     [description]   = Google Chrome
     *                     [active]          = true|false
     *                     [postfix]         = chrome
     *
     * @var array
     */
    private static $_builderInfo = array();

    /*
     * Contains the Browser informations
     * @var array
     */
    private static $_browsers = array();

    /*
     * frontend theme
     * @var string
     */
    private static $_frontendTheme;

    /*
     * backend theme
     * @var string
     */
    private static $_backendTheme;

    /*
     * path to the frontend directory
     * @var string
     */
    private static $_frontendPath;

    /*
     * path to the backend directory
     * @var string
     */
    private static $_backendPath;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        // initializing
        self::setFrontendPath(ROOT . 'themes' . DS . 'frontend');
        self::setBackendPath(ROOT . 'themes' . DS . 'backend');
        self::setFrontendTheme('standard');
        self::setBackendTheme('admin');
        self::addBrowser('default', 'Standard Browser (Mozilla)', true, '');
    }

    /**
     * -------------------------------------------------------------------------------------------------
     * Compiler
     * -------------------------------------------------------------------------------------------------
     */
    public function build($index)
    {

        #-----------------------------------------------------------------------------------------------------------------
        # Prepare
        #-----------------------------------------------------------------------------------------------------------------
        $builderInfo = self::getBuilderInfo();
        #Clansuite_Debug::printR( $builderInfo );

        $browser = $builderInfo['browsers'];
        $postfix = $browser[$index]['postfix'];
        if($postfix != '')
        {
            $postfix = '_' . $postfix;
        }
        $builderINI = 'cssbuilder' . $postfix . '.ini';

        // Read Core INI-File
        $coreINI = ROOT . 'themes' . DS . 'core' . DS . 'css' . DS . 'csfw' . DS . $builderINI;
        $coreInfo = $this->read_properties($coreINI);
        $coreCssName = $coreInfo['cssname'] . $postfix . '.css';
        $coreadditionalFiles = array();

        /* ----- prepare core ----- */
        if(true === $builderInfo['compileCore'])
        {
            /* Core-Info */
            $corePath = ROOT . $coreInfo['path'];
            $coreFiles = explode(',', $coreInfo['files']);
            if(strlen($coreInfo['additionalFiles']) > 0)
            {
                $coreadditionalFiles = explode(',', $coreInfo['additionalFiles']);
            }
            $core_compact = $this->getCoreCompactHeader($coreInfo);
        }

        /* ----- prepare frontend theme ----- */
        if(true === $builderInfo['compileThemeFrontend'])
        {
            if(mb_substr($builderInfo['themeFrontendPath'], strlen($builderInfo['themeFrontendPath']) - 1) == '/' ||
                    mb_substr($builderInfo['themeFrontendPath'], strlen($builderInfo['themeFrontendPath']) - 1) == '\/')
            {
                $builderInfo['themeFrontendPath'] = mb_substr($builderInfo['themeFrontendPath'], strlen($builderInfo['themeFrontendPath']) - 1);
            }

            // Read INI-File
            $themeINI = $builderInfo['themeFrontendPath'] . DS . $builderInfo['themeFrontend'] . DS . 'css' . DS . $builderINI;
            $themeInfo = $this->read_properties($themeINI);
            $themeInfo['path'] = str_replace("{theme}", $builderInfo['themeFrontend'], $themeInfo['path']);

            /* Theme-Info */
            $themePath = ROOT . $themeInfo['path'];
            $themeCssName = $themeInfo['cssname'] . $postfix . '.css';
            $themeFiles = explode(',', $themeInfo['files']);
            if(strlen($themeInfo['additionalFiles']) > 0)
            {
                $themeadditionalFiles = explode(',', $themeInfo['additionalFiles']);
            }
            else
            {
                $themeadditionalFiles = array();
            }

            $theme_compact = $this->getThemeCompactHeader($themeInfo);
        }

        #Clansuite_Debug::printR( $themeFiles );

        /* ----- prepare backend theme ----- */
        if(true === $builderInfo['compileThemeBackend'])
        {
            if(mb_substr($builderInfo['themeBackendPath'], strlen($builderInfo['themeBackendPath']) - 1) == '/' ||
                    mb_substr($builderInfo['themeBackendPath'], strlen($builderInfo['themeBackendPath']) - 1) == '\/')
            {
                $builderInfo['themeBackendPath'] = mb_substr($builderInfo['themeBackendPath'], strlen($builderInfo['themeBackendPath']) - 1);
            }
            // Read INI-File
            $themeBackINI = $builderInfo['themeBackendPath'] . DS . $builderInfo['themeBackend'] . DS . 'css' . DS . $builderINI;
            $themeBackInfo = $this->read_properties($themeBackINI);
            $themeBackInfo['path'] = str_replace("{theme}", $builderInfo['themeBackend'], $themeBackInfo['path']);

            /* Theme-Info */
            $themeBackPath = ROOT . $themeBackInfo['path'];
            $themeBackCssName = $themeBackInfo['cssname'] . $postfix . '.css';
            $themeBackFiles = explode(',', $themeBackInfo['files']);

            if(strlen($themeBackInfo['additionalFiles']) > 0)
            {
                $themeBackadditionalFiles = explode(',', $themeBackInfo['additionalFiles']);
            }
            else
            {
                $themeBackadditionalFiles = array();
            }

            $themeBack_compact = $this->getThemeCompactHeader($themeBackInfo);
        }

        $_compact = $this->getCompactHeader($browser[$index]['description']);
        $html = '';

        #-----------------------------------------------------------------------------------------------------------------
        # Build Core CSS
        #-----------------------------------------------------------------------------------------------------------------
        if(true === $builderInfo['compileCore'])
        {
            $_comp = '';
            $_comp .= $core_compact;

            foreach($coreFiles as $filename)
            {
                $content = self::load_stylesheet($corePath . $filename, true);
                $_comp .= "/* [" . basename($filename) . "] */" . "\n";
                $_comp .= $content . "\n";
            }

            $this->save_stylesheet($corePath . $coreCssName, $_comp);

            $html .= '<p class="cmBoxMessage" style="padding-left:50px;"><b>Core Import File:</b>';
            $html .= '&nbsp;&nbsp;' . $corePath;
            $html .= '<span class="cmSuccessFilenameColor"><b>' . $coreCssName . '</b></span> wurde generiert</p>';
        }

        #-----------------------------------------------------------------------------------------------------------------
        # Build Frontend Theme CSS
        #-----------------------------------------------------------------------------------------------------------------
        /**
         * create Info + compiled frontend theme stylesheet
         */
        if(true === $builderInfo['compileThemeFrontend'])
        {
            $coreImp = '.';
            $_comp = $_compact;

            if(true === $builderInfo['coreImport'])
            {
                $_comp .= "/** [Core Import] */\n";
                $_comp .= "@import url('../../../core/css/" . $coreCssName . "');\n\n";
                $coreImp = ' und die Core importiert.';
            }

            if(count($coreadditionalFiles) > 0)
            {
                foreach($coreadditionalFiles as $filename)
                {
                    $_comp .= "/* Import additional file: [" . trim($filename) . "] */" . "\n";
                    $_comp .= "@import url('" . trim($filename) . "');\n\n";
                }
            }

            if(count($themeadditionalFiles) > 0)
            {
                foreach($themeadditionalFiles as $filename)
                {
                    $_comp .= "/* Import additional file: [" . trim($filename) . "] */" . "\n";
                    $_comp .= "@import url('" . trim($filename) . "');\n\n";
                }
            }

            $_comp .= $theme_compact;

            foreach($themeFiles as $filename)
            {
                $content = self::load_stylesheet($themePath . $filename, true);
                $_comp .= "/* [" . basename($filename) . "] */" . "\n";
                $_comp .= $content . "\n";
            }

            $this->save_stylesheet($themePath . $themeCssName, $_comp);

            $html .= '<p class="cmBoxMessage" style="padding-left:50px;"><b>Frontend Theme Import File:</b>';
            $html .= '&nbsp;&nbsp;' . $themePath;
            $html .= '<span class="cmSuccessFilenameColor"><b>' . $themeCssName . '</b></span> wurde generiert' . $coreImp . '</p>';
        }

        #-----------------------------------------------------------------------------------------------------------------
        # Build Backend Theme CSS
        #-----------------------------------------------------------------------------------------------------------------
        /**
         * create Info + compiled backend theme stylesheet
         */
        if(true === $builderInfo['compileThemeBackend'])
        {
            $coreImp = '.';
            $_comp = $_compact;

            if(true === $builderInfo['coreImport'])
            {
                $_comp .= "/** [Core Import] */\n";
                $_comp .= "@import url('../../../core/css/" . $coreCssName . "');\n\n";
                $coreImp = ' und die Core importiert.';
            }

            if(count($coreadditionalFiles) > 0)
            {
                foreach($coreadditionalFiles as $filename)
                {
                    $_comp .= "/* Import additional file: [" . trim($filename) . "] */" . "\n";
                    $_comp .= "@import url('" . trim($filename) . "');\n\n";
                }
            }

            if(count($themeBackadditionalFiles) > 0)
            {
                foreach($themeBackadditionalFiles as $filename)
                {
                    $_comp .= "/* Importing additional css file: [" . trim($filename) . "] */" . "\n";
                    $_comp .= "@import url('" . trim($filename) . "');\n\n";
                }
            }

            $_comp .= $themeBack_compact;

            foreach($themeBackFiles as $filename)
            {
                $content = self::load_stylesheet($themeBackPath . $filename, true);
                $_comp .= "/* [" . basename($filename) . "] */" . "\n";
                $_comp .= $content . "\n";
            }

            $this->save_stylesheet($themeBackPath.$themeBackCssName,$_comp);

            $html .= '<p class="cmBoxMessage" style="padding-left:50px;"><b>Backend Theme Import File:</b>';
            $html .= '&nbsp;&nbsp;' .$themeBackPath;
            $html .= '<span class="cmSuccessFilenameColor"><b>'.$themeBackCssName .'</b></span> wurde generiert'.$coreImp.'</p>';
        }

        return $html;
    }


    /**
     * -------------------------------------------------------------------------------------------------
     * read_core_definition
     * -------------------------------------------------------------------------------------------------
     */
    protected function read_properties( $inifile )
    {
        $iniArray = parse_ini_file($inifile);
        #Clansuite_Debug::printR( $iniArray );
        $iniArray['files'] = str_replace( " ", "", $iniArray['files']);
        $iniArray['files'] = str_replace( "\t", "", $iniArray['files']);
        $iniArray['files'] = str_replace( "\r\n", "", $iniArray['files']);
        $iniArray['files'] = str_replace( "\r", "", $iniArray['files']);
        $iniArray['files'] = str_replace( "\n", "", $iniArray['files']);
        if( mb_substr( $iniArray['files'], strlen($iniArray['files'])-1) == ',' )
        {
            $iniArray['files'] = mb_substr( $iniArray['files'], 0, strlen($iniArray['files'])-1);
        }
        return $iniArray;
    }

    protected function getCompactHeader( $browserInfo = '' )
    {
        $compact = '';
        $compact =  "/**"."\n";
        $compact .= " * ---------------------------------------------------------------------------------------------------"."\n";
        $compact .= " * CSS2 Framework (CSFW)"."\n";
        $compact .= " * ---------------------------------------------------------------------------------------------------"."\n";
        $compact .= " * @author       Paul Brand <info@isp-tenerife.net>"."\n";
        $compact .= " * @package      CSFW"."\n";
        $compact .= " * @subpackage   Core"."\n";
        $compact .= " * @version      1.0"."\n";
        $compact .= " * ---------------------------------------------------------------------------------------------------"."\n";
        $compact .= " * @description  Created for - ".$browserInfo."\n";
        $compact .= " * ---------------------------------------------------------------------------------------------------"."\n";
        $compact .= " */"."\n";
        $compact .= "\n";
        $compact .= '@charset "UTF-8";';
        $compact .= "\n";
        $compact .= "\n";

        return $compact;
    }

    protected function getCoreCompactHeader( $coreInfo = '' )
    {
        $core_compact =  '';
        $core_compact =  "/**"."\n";
        $core_compact .= " * ----------------------------------------------------------------------------------------------"."\n";
        $core_compact .= " * Framework:    " .$coreInfo['framework']. "\n";
        $core_compact .= " * Description:  " .$coreInfo['description']. "\n";
        $core_compact .= " * Author:       " .$coreInfo['author']. "\n";
        $core_compact .= " * Version:      " .$coreInfo['version']. "\n";
        $core_compact .= " * Version-Date: " .$coreInfo['date']. "\n";
        $core_compact .= " * Created:      " .date('Y-m-d H:i:s', time()). "\n";
        $core_compact .= " * ----------------------------------------------------------------------------------------------"."\n";
        $core_compact .= " */"."\n";

        return $core_compact;
    }

    protected function getThemeCompactHeader( $themeInfo = '' )
    {
        $theme_compact = '';
        $theme_compact =  "/**"."\n";
        $theme_compact .= " * ---------------------------------------------------------------------------------------------"."\n";
        $theme_compact .= " * Framework:    " .$themeInfo['framework']. "\n";
        $theme_compact .= " * Description:  " .$themeInfo['description']. "\n";
        $theme_compact .= " * Author:       " .$themeInfo['author']. "\n";
        $theme_compact .= " * Version:      " .$themeInfo['version']. "\n";
        $theme_compact .= " * Version-Date: " .$themeInfo['date']. "\n";
        $theme_compact .= " * Created:      " .date('Y-m-d H:i:s', time()). "\n";
        $theme_compact .= " * ---------------------------------------------------------------------------------------------"."\n";
        $theme_compact .= " */"."\n";

        return $theme_compact;
    }

    /**
     * -------------------------------------------------------------------------------------------------
     * save_stylesheet
     * -------------------------------------------------------------------------------------------------
     * save new stylesheet import file
     */
    protected function save_stylesheet($comp_filename, $_compact)
    {
        if(!$filehandle = fopen($comp_filename, 'wb'))
        {
            echo _('Could not open file: ') . $comp_filename;
            return false;
        }

        if(fwrite($filehandle, $_compact) == false)
        {
            echo _('Could not write to file: ') . $comp_filename;
            return false;
        }
        fclose($filehandle);
    }

    /**
     * -------------------------------------------------------------------------------------------------
     * load_stylesheet
     * -------------------------------------------------------------------------------------------------
     *
     */
    protected static function load_stylesheet($file, $optimize = true)
    {
        $contents = '';
        if(file_exists($file))
        {
            # Load the local CSS stylesheet.
            $contents = file_get_contents($file);

            # image path anpassen
            $contents = str_replace('../../', '../', $contents);

            # Change to the current stylesheet's directory.
            $cwd = getcwd();
            chdir(dirname($file));

            # Process the stylesheet.
            $contents = self::load_stylesheet_content($contents, $optimize);

            # Change back directory.
            chdir($cwd);
        }

        return $contents;
    }

    /**
     * -------------------------------------------------------------------------------------------------
     * load_stylesheet_content
     * -------------------------------------------------------------------------------------------------
     * stylesheet compiler
     */
    protected static function load_stylesheet_content($contents, $optimize = false)
    {
        # Remove multiple charset declarations for standards compliance (and fixing Safari problems).
        $contents = preg_replace('/^@charset\s+[\'"](\S*)\b[\'"];/i', '', $contents);

        if ($optimize) {
            // Regexp to match comment blocks.
            $comment     = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';

            // Regexp to match double quoted strings.
            $double_quot = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';

            // Regexp to match single quoted strings.
            $single_quot = "'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'";

            // Strip all comment blocks, but keep double/single quoted strings.
            $contents = preg_replace( "<($double_quot|$single_quot)|$comment>Ss", "$1", $contents );

            /**
             * Remove certain whitespace.
             * There are different conditions for removing leading and trailing
             * whitespace. To be able to use a single backreference in the replacement
             * string, the outer pattern uses the ?| modifier, which makes all contained
             * subpatterns appear in \1.
             * @see http://php.net/manual/en/regexp.reference.subpatterns.php
             */
            $contents = preg_replace('<
                (?|
                # Strip leading and trailing whitespace.
                \s*([@{};,])\s*
                # Strip only leading whitespace from:
                # - Closing parenthesis: Retain "@media (bar) and foo".
                | \s+([\)])
                # Strip only trailing whitespace from:
                # - Opening parenthesis: Retain "@media (bar) and foo".
                # - Colon: Retain :pseudo-selectors.
                | ([\(:])\s+
                )
                >xS',
                '\1',
                $contents
            );

            # End the file with a new line.
            $contents .= "\n";
        }

        # Replaces @import commands with the actual stylesheet content.
        # This happens recursively but omits external files.
        $contents = preg_replace_callback('/@import\s*(?:url\(\s*)?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\s*\)?\s*;/', 'self::load_stylesheet', $contents);

        return $contents;
    }


    /**
     * add browser
     *
     * @param $shortname string browser schortcut ( e.g. ie )
     * @param $description  string Browser information ( e.g. Internet Explorer )
     * @param $active  boolean browser is active for compile or not
     * @param $postfix string browser postfix for the import.css  ( e.g. 'ie' will generate import_ie.css )
     */
    public static function addBrowser($shortname, $description = '', $active = false, $postfix = '')
    {
        if(!empty($shortname))
        {
            $browserArray = self::getBrowsers();

            $browserArray[$shortname]['short'] = $shortname;
            $browserArray[$shortname]['description'] = $description;
            $browserArray[$shortname]['active'] = $active;
            $browserArray[$shortname]['postfix'] = $postfix;

            self::setBrowsers($browserArray);
        }
    }

    /**
     * =========================================
     * Getter methodes
     * =========================================
     */
    public static function getFrontendPath()
    {
        return self::$_frontendPath;
    }

    public static function getBackendPath()
    {
        return self::$_backendPath;
    }

    public static function getFrontendTheme()
    {
        return self::$_frontendTheme;
    }

    public static function getBackendTheme()
    {
        return self::$_backendTheme;
    }

    public static function getBrowsers()
    {
        return self::$_browsers;
    }

    public static function getBuilderInfo()
    {
        return self::$_builderInfo;
    }

    /**
     * =========================================
     * Setter methodes
     * =========================================
     */
    public static function setFrontendPath($value)
    {
        self::$_frontendPath = $value;
    }

    public static function setBackendPath($value)
    {
        self::$_backendPath = $value;
    }

    public static function setFrontendTheme($value)
    {
        self::$_frontendTheme = $value;
    }

    public static function setBackendTheme($value)
    {
        self::$_backendTheme = $value;
    }

    public static function setBrowsers($data)
    {
        self::$_browsers = $data;
    }

    /**
     * BuilderInfo contains all definitions for the builder
     */
    public static function setBuilderInfo($data)
    {
        $aBuilderInfo = array();

        // initialize width default while $data not declared
        if(!is_array($data) || count($data) == 0)
        {
            $aBuilderInfo['compileCore'] = false;
            $aBuilderInfo['coreImport'] = true;

            $aBuilderInfo['compileThemeFrontend'] = true;
            $aBuilderInfo['themeFrontendPath'] = self::getFrontendPath();
            $aBuilderInfo['themeFrontend'] = self::getFrontendTheme();

            $aBuilderInfo['compileThemeBackend'] = false;
            $aBuilderInfo['themeBackendPath'] = self::getBackendPath();
            $aBuilderInfo['themeBackend'] = self::getBackendTheme();
            $aBuilderInfo['browsers'] = self::getBrowsers();
        }
        else
        {
            $aBuilderInfo = $data;
        }

        $aBuilderInfo['info']['generator_name'] = self::$generatorName;
        $aBuilderInfo['info']['generator_version'] = self::$generatorVersion;
        $aBuilderInfo['info']['generator_version_date'] = self::$generatorVersionDate;

        self::$_builderInfo = $aBuilderInfo;
    }
}
?>