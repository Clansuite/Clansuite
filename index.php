<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    *        _\|/_
    *        (o o)
    +-----oOO-{_}-OOo------------------------------------------------------------------+
    |                                                                                  |
    | LICENSE:                                                                         |
    |                                                                                  |
    |    This program is free software; you can redistribute it and/or modify          |
    |    it under the terms of the GNU General Public License as published by          |
    |    the Free Software Foundation; either version 2 of the License, or             |
    |    (at your option) any later version.                                           |
    |                                                                                  |
    |    This program is distributed in the hope that it will be useful,               |
    |    but WITHOUT ANY WARRANTY; without even the implied warranty of                |
    |    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                 |
    |    GNU General Public License for more details.                                  |
    |                                                                                  |
    |    You should have received a copy of the GNU General Public License             |
    |    along with this program; if not, write to the Free Software                   |
    |    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA    |
    |                                                                                  |
    +----------------------------------------------------------------------------------+
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

   /** =====================================================================
    *    WARNING: DO NOT MODIFY FILES, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *             READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

# Define security constant
define('IN_CS', true);

/**
 *  ==========================================
 *     Configuration, Initalization, Loader
 *  ==========================================
 */
# Check if clansuite.config.php is found, else we are not installed at all, so redirect to installation page
if ( is_file('configuration/clansuite.config.php' ) == false ) { header( 'Location: installation/index.php' ); exit; }
# Check if install.php is still available, so we are installed but without security steps performed
#if ( is_file( 'installation/install.php') == true ) { header( 'Location: installation/check_security.php'); exit; }
# requires configuration & gets a config to work with
require 'core/config/ini.config.php';
$config = Clansuite_Config_IniHandler::readConfig('configuration/clansuite.config.php'); #clansuite_xdebug::printR($config);

# initialize constants / errorhandling / ini_sets / paths
require 'core/bootstrap/clansuite.init.php';
# get loaders and register/overwrite spl_autoload handling
require 'core/bootstrap/clansuite.loader.php';
clansuite_loader::register_autoload();

# Set Exception Handler
set_exception_handler(array(new Clansuite_Exception, 'clansuite_exception_handler'));

# Set Error Handler
new Clansuite_Errorhandler(new Clansuite_Config);

/**
 *  ============================================
 *     Dependency Injector + Register Classes
 *  ============================================
 */
# Setup Phemto
require ROOT_LIBRARIES.'phemto/phemto.php';
$injector = new Phemto();

# core classes to load
$core_classes = array(
'Clansuite_Config', 'Clansuite_HttpRequest', 'Clansuite_HttpResponse', 'Clansuite_FilterManager',
'Clansuite_Doctrine','Clansuite_Localization', 'Clansuite_Security', 'Clansuite_Inputfilter', 'Clansuite_Statistics'
);
foreach($core_classes as $class) { $injector->register(new Singleton($class)); }

# filters to load
$prefilter_classes = array(
'maintenance', 'get_user', 'language_via_get', 'theme_via_get', 'set_module_language', 'set_breadcrumbs',
'php_debug_console', 'startup_checks', 'statistics');
foreach($prefilter_classes as $class) { $injector->register($class); } # register the filters

$postfilter_classes = array(
#empty-at-this-time
);
foreach($postfilter_classes as $class) { $injector->register($class); } # register the filters

# Connect DB, that is needed for session & user rights management
$injector->instantiate('Clansuite_Doctrine');

# Initialize Session, then register the session-depending User-Object manually
Clansuite_Session::getInstance($injector);
$injector->register(new Singleton('Clansuite_User'));

/**
 *  ===================================================================
 *     Request & Response + Frontcontroller + Filters + processRequest
 *  ===================================================================
 */

# Get request and response objects for Filters and RequestProcessing
$request  = $injector->instantiate('Clansuite_HttpRequest');
$response = $injector->instantiate('Clansuite_HttpResponse');

# Setup Frontcontroller and ControllerResolver; add default module and action; start passing $injector around
$clansuite = new Clansuite_FrontController(
                 new Clansuite_ModuleController_Resolver($config['defaults']['default_module']),
                 new Clansuite_ActionController_Resolver($config['defaults']['default_action']),
                 $injector);

/**
 * Prefilters or Postfilters
 * - PRE-Filters are executed before ModuleAction is triggered
 *   Examples: caching, theme
 * - POST-Filters are executed afterwards, but before view rendering
 *   Examples: output compression, character set modifications, breadcrumbs
 */
foreach($prefilter_classes as $class)
{
    $clansuite->addPrefilter($injector->instantiate($class));
}
foreach($postfilter_classes as $class)
{
    $clansuite->addPostfilter($injector->instantiate($class));
}

# Take off.
$clansuite->processRequest($request, $response);

# XDebug has the last word: Stop tracing and show debugging infos.
if(XDEBUG)
{
    clansuite_xdebug::end_xdebug();
}

if(DEBUG)
{
    $injector->instantiate('Clansuite_Doctrine')->displayProfilingHTML();
}
?>