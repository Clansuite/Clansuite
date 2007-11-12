<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf
    * http://www.clansuite.com/
    * All rights reserved
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

   /** =====================================================================
    *  	 WARNING: DO NOT MODIFY FILES, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *             READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */
# Setup XDebug
define ('XDBUG', 0); if(XDBUG){ require 'clansuite.xdebug.php'; clansuite_xdebug::start_xdebug(); }

# Define security constant
define('IN_CS', true);

# Benchmarking
# todo: error-detection within benchmarkarrays
#require 'core/benchmark.class.php';
#benchmark::timemarker('begin', 'Exectime:');

/**
 *  ==========================================
 *     Configuration, Initalization, Loader
 *  ==========================================
 */
# Check if clansuite.class.php is found, else redirect to installation page
if ( !is_file( 'clansuite.config.php' ) ) { header( 'Location: installation/index.php' ); exit; }
# requires configuration & gets a config to work with
require 'clansuite.config.php';
$config = new configuration;
# initialize constants / errorhandling / ini_sets / paths
require 'clansuite.init.php';
# get loaders and register/overwrite spl_autoload handling
require 'clansuite.loader.php';
clansuite_loader::register_autoload();

/**
 *  ============================================
 *     Dependency Injector + Register Classes
 *  ============================================
 */
require ROOT_LIBRARIES.'/phemto/phemto.php';
$injector = new Phemto();
$classes = array(
# Core
'configuration', 'httprequest', 'httpresponse', 'filtermanager', 'db',
'language', 'errorhandler', 'trail', 'security', 'input', 'functions', 'statistic',
#Filters
'language_via_get', 'theme_via_get', 'get_user'
);
foreach($classes as $class) { $injector->register(new Singleton($class)); }

# Initialize Session, then register the session-depending User-Object manually
new session($injector);
$injector->register('user');

/**
 *  ================================================
 *     Frontcontroller + Filters + processRequest
 *  ================================================
 */
# Setup Frontcontroller and ControllerResolver; add default module and action; start passing $injector around
$clansuite = new clansuite_frontcontroller(new clansuite_controllerresolver($config['default_module'],$config['default_action']),$injector);

# Get request and response objects for Filters and RequestProcessing
$request  = $injector->instantiate('httprequest');
$response = $injector->instantiate('httpresponse');

/**
 * Prefilters or Postfilters
 * - PRE-Filters are executed before ModuleAction is triggered
 *   Examples: caching, theme
 * - POST-Filters are executed afterwards, but before view rendering
 *   Examples: output compression, character set modifications, breadcrumbs
 */
$clansuite->addPrefilter($injector->instantiate('get_user'));
$clansuite->addPrefilter($injector->instantiate('language_via_get'));
$clansuite->addPrefilter($injector->instantiate('theme_via_get'));
#$clansuite->addPostfilter($injector->instantiate('build_breadcrumb'));

# Take off.
$clansuite->processRequest($request, $response);

# Stop debugging and show debugging infos.
if(XDBUG){ clansuite_xdebug::end_xdebug(); }

#benchmark::timemarker('end', 'Exectime:');
#echo benchmark::timemarker('list');
?>