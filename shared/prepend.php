<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

// TOC of File: 
// 1. Settings: $_CONFIG, DEFINES, VARS
// 2. load libraries

############ $_CONFIG DEFAULTS ############
$_CONFIG['version'] = "Clansuite 0.1 - alpha";
$_CONFIG['author'] = "Jens-Andre Koch <knd.vain@gmail.com>";
$_CONFIG['starttime'] = (float) array_sum(explode(' ', microtime())); // Benchmark

############ Define -> Constants ############
define('CLANSUITE', '1');
define('DEBUG', '0');
#ini_set(xdebug.auto_profile_mode,1 );
#xdebug_start_profiling() ;

## Pfade einmal ermitteln und als Defines setzen
# Das ja doll hier: Welche Pfade brauchst du denn nun wirklich, nimm Unbenötigte raus.
# todo: Abhängikeiten checken und verkleinern
if (!defined('ROOT')) 
{     
define('ROOT', dirname(dirname(__FILE__).'../'));
define('BASE_URL_SEED', 'http://'.$_SERVER['SERVER_NAME']);
if (dirname($_SERVER['PHP_SELF']) == "\\")
  define('BASE_URL', BASE_URL_SEED);
else 
  define('BASE_URL', BASE_URL_SEED.dirname($_SERVER['PHP_SELF']));
}
define ('WWW_ROOT', '/work/clansuite');

/**
 * Cookie Constants - these are the parameters
 * to the setcookie function call, change them
 * if necessary to fit your website. 
 * <http://www.php.net/manual/en/function.setcookie.php>
 */
define("COOKIE_EXPIRE", 60*60*24*100);  //100 days by default
define("COOKIE_PATH", "/");  //Available in whole domain

# Scanner für aktivierte Classes ?
# oder scan in Db? TODO
#$classes = get_declared_classes();
#foreach $classes as $classname
#{ print new Reflection_Class($classname);
#} tiefstes php5


############ INI_SETS ############
ini_set('arg_separator.output','&amp;');
ini_set('register_globals','off');
ini_set('session.use_trans_sid','0');
ini_set('session.use_cookies','1');
ini_set('session.use_only_cookies','1');
ini_set('display_errors', 1);
ini_set('magic_quotes_runtime', 0);
ini_set('magic_quotes_gpc', 0 );
ini_set('zend.ze1_compatibility_mode', 0);
ini_set('zlib.output_compression_level', 3); // gzip the output
#ob_start('ob_gzhandler');
header('X-Powered-By: Clansuite CMS/'.$_CONFIG['version'].' (clansuite.sourceforge.net)',false);

#####################################################################
##################### Load and Initialize Libraries  ################
#####################################################################

############ Error Reporting & Errorhandler ############
require_once (dirname(__FILE__).'/class.errorhandler.php');
error_reporting(E_ALL);
#error_reporting(E_STRICT); 
set_error_handler('raiseError');

############ Lib für unsortiertes ############
include dirname(__FILE__).'/unsortedlib.php';

############ Database ############
include dirname(__FILE__).'/class.db.php';
require_once (dirname(__FILE__).'/db.settings.php');

############ Detect Language by first 2 chars ############
// todo: 1. UserLanguage ermitteln und als SessionVar des Users setzen
// todo: 2. DropDown oder Switch auf Seite ermöglicht Änderung dieser Session var
// 3. je nach Wert wird der entsprechende Sprachabschnitt aus dem XML geholt

// Detection
// e.g. "en-uk" and "en-us" are taken for "en"
if (!$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) { 
$language = "en"; // Default Language: English
}
// Init TMX-LanguageClass
require_once(dirname(__FILE__).'/class.tmx.php');
$CoreLanguageFile = (dirname(__FILE__).'/language/core.xml');
$CoreLangInit = new TMXResourceBundle($CoreLanguageFile, "DE"); ## hardcoded german
$corelanguage = $CoreLangInit->getResource(); 

############ Template Class: Smarty ###########

// Smarty Library Pfad setzen und laden
#define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/shared/smarty/');
require_once(dirname(__FILE__).'/smarty/Smarty.class.php');
$MainPage = new Smarty();

// Allgemeiner Templatepfad 
$MainPage->template_dir =  $_SERVER['DOCUMENT_ROOT'] . '/'.  WWW_ROOT . '/' . 'templates';
$MainPage->compile_dir =  $_SERVER['DOCUMENT_ROOT'] . '/'.  WWW_ROOT . '/' . '/templates/templates_c/';
$MainPage->config_dir =  $_SERVER['DOCUMENT_ROOT'] . '/'.  WWW_ROOT . '/' . '/configs/';
$MainPage->cache_dir = $_SERVER['DOCUMENT_ROOT'] . '/'.  WWW_ROOT . '/' . '/cache/';

// Smarty Settings
$MainPage->compile_check = true;
$MainPage->debugging = true;

// Var Assignments
$MainPage->assign('clansuite_version', $_CONFIG['version']);
$MainPage->assign('corelanguage', $corelanguage);
$MainPage->assign('authed', $_SESSION['User']['authed'] );
$MainPage->assign('username', $_SESSION['User']['nick'] );
$MainPage->assign('usergroup', "ADMIN" //$_SESSION['User']['groups'] 
);

// todo: docu, smarty_extensions library
// einmal-funktion zur initialisierung der modultemplatepfade / language
function ModulInit($modulname, $title)
   { global $ModulPage;

        $ModulPage = new Smarty();
      	$ModulPage->template_dir =  ROOT.'/module/'.$modulname . '/templates';
		$ModulPage->compile_dir =  ROOT.'/module/'. $modulname . '/templates/templates_c/';
		$ModulPage->config_dir =  ROOT.'/module/'. $modulname . '/templates/configs/';
		$ModulPage->cache_dir = ROOT.'/module/'. $modulname . '/templates/cache/';

        $ModulPage->caching = true;
        $ModulPage->compile_check = true;
		$ModulPage->debugging = true;
		
        $ModulPage->assign('modulname', '$modulname');
        $ModulPage->assign('title', '$title');
        
        //ModulLanguage Init
        $ModulLanguageFile = ROOT.'/module/'.$modulname.'/language/'.$modulname.'.language.xml';
		$ModulLangInit = new TMXResourceBundle($ModulLanguageFile, "DE"); // english
		$modullanguage = $ModulLangInit->getResource(); // language array for english
		$ModulPage->assign('modullanguage', $modullanguage);
        
 }
 
############ PHP InputFilter ############ 
require_once (dirname(__FILE__).'/class.inputfilter.php');
$queryFilter = new InputFilter(); global $queryFilter; 			# create filter object

############ Session Management ############
require_once(dirname(__FILE__).'/class.session.php');

############ User ############
require_once(dirname(__FILE__).'/user.php');

############ Modules ############
require_once (dirname(__FILE__).'/class.module.php');

#####################################################################
##################### END OF PREPEND ################################
#####################################################################
?>