<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005 - onwards)
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
if ( is_file( 'clansuite.config.php' ) == false ) { header( 'Location: installation/index.php' ); exit; }
# Check if install.php is still available, so we are installed but without security steps performed
#if ( is_file( 'installation/install.php') == true ) { header( 'Location: installation/check_security.php'); exit; }
# requires configuration & gets a config to work with
require 'core/clansuite_config.class.php';
$config = Clansuite_Config::readConfig('clansuite.config.php'); #clansuite_xdebug::printR($config);

# Setup XDebug
define('XDBUG', $config['error']['xdebug']); if(XDBUG){ require 'core/clansuite.xdebug.php'; clansuite_xdebug::start_xdebug(); }

# initialize constants / errorhandling / ini_sets / paths
require 'core/clansuite.init.php';
# get loaders and register/overwrite spl_autoload handling
require 'core/clansuite.loader.php';
clansuite_loader::register_autoload();

/**
 *  ============================================
 *     Dependency Injector + Register Classes
 *  ============================================
 */
# Setup Phemto
require ROOT_LIBRARIES.'/phemto/phemto.php';
$injector = new Phemto();

# core classes to load
$core_classes = array(
'Clansuite_Config', 'errorhandler', 'httprequest', 'httpresponse', 'filtermanager',
'db', 'clansuite_doctrine','localization', 'Clansuite_Security', 'input', 'functions'
);
foreach($core_classes as $class) { $injector->register(new Singleton($class)); }

# filters to load
$prefilter_classes = array(
'maintenance', 'get_user', 'language_via_get', 'theme_via_get', 'set_module_language', 'set_breadcrumbs',
'php_debug_console');
foreach($prefilter_classes as $class) { $injector->register($class); } # register the filters

$postfilter_classes = array(
#empty-at-this-time
);
foreach($postfilter_classes as $class) { $injector->register($class); } # register the filters

# Connect DB, that is needed for session & user rights management
$injector->instantiate('clansuite_doctrine');

# Initialize Session, then register the session-depending User-Object manually
Clansuite_Session::getInstance($injector);
$injector->register('Clansuite_User');

/**
 *  ===================================================================
 *     Request & Response + Frontcontroller + Filters + processRequest
 *  ===================================================================
 */

# Get request and response objects for Filters and RequestProcessing
$request  = $injector->instantiate('httprequest');
$response = $injector->instantiate('httpresponse');

# Setup Frontcontroller and ControllerResolver; add default module and action; start passing $injector around
$clansuite = new Clansuite_FrontController(new Clansuite_ModuleController_Resolver($config['defaults']['default_module'],$config['defaults']['default_action']),$injector);

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

# Stop debugging and show debugging infos.
if(XDBUG){ clansuite_xdebug::end_xdebug(); }

?>