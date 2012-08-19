<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
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

/**
 * Configuration Options for Tests
 * ===================================================
 */

// Toggle for CodeCoverage. (It depends on the PHP extensions Xdebug and SQlite.)
define('PERFORM_CODECOVERAGE', false);

// Toggle, whether to run WebTests or not.
define('PERFORM_WEBTESTS', false);

// ===================================================

// Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('html_errors', false);
ini_set('log_errors', false);

// Tests take some time and memory
/**
 * The test for safe_mode is needed in order to avoid the message:
 * "Warning: set_time_limit() has been disabled for security reasons".
 * SAFE_MODE has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * This check is added to get tests without errors on crappy, outdated hosters.
 */
if(ini_get('safe_mode') == false){ set_time_limit(0); }
ini_set('memory_limit', '256M');

// PHP Version Check
$REQUIRED_PHP_VERSION = '5.3.2';
if (version_compare(PHP_VERSION, $REQUIRED_PHP_VERSION, '<=') === true) {
    exit('Your PHP Version is <b><font color="#FF0000">' . PHP_VERSION . '</font></b>.
         Clansuite Testsuite requires PHP Version <b><font color="#4CC417">' . $REQUIRED_PHP_VERSION . '</font></b> or newer.');
}
unset($REQUIRED_PHP_VERSION);

// well this should be defined in PHP.ini.. fallback, if you are lazy.
date_default_timezone_set('Europe/Berlin');

$paths = array(
    // add the TEST SUBJECT dir
    realpath(dirname(__DIR__) . '/framework'),      // /trunk/framework
    realpath(dirname(__DIR__) . '/application'),    // /trunk/application
    realpath(dirname(__DIR__)),                     // /trunk
    // adjust include path to TESTS dir
    realpath(__DIR__),                  // /trunk/tests
    realpath(__DIR__ . '/unittests'),   // /trunk/tests/unittests
);
#var_dump($paths);

// attach original include paths
set_include_path(implode($paths, PATH_SEPARATOR) . PATH_SEPARATOR . get_include_path());
unset($paths);

// needed if, run from CLI
if (empty($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = gethostname();
}

//  acquire clansuite path constants
include dirname(__DIR__) . '/application/bootstrap.php';
\Clansuite\CMS::define_ConstantsAndPaths();
\Clansuite\CMS::initialize_Loader();

\Koch\Localization\Utf8::initialize();

/**
 * Constants
 *
 * Constants must be defined, after initialize_paths(),
 * because of the automatic apc constants cache in
 * define_ConstantsAndPaths().
 */
define('REWRITE_ENGINE_ON', 1);
define('TESTSUBJECT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR); // /../tests (trunk)

/**
 * We might need some debug utils,
 * when we are not in CLI mode.
 */
if (isCli() === false) {
    require_once KOCH . 'Debug/Debug.php';
}

/**
 * Gettext Replacement
 *
 * @param type $msgid
 */
if (!function_exists('_')) {
    function _($msgid)
    {
        return $msgid;
    }
}

function isCli()
{
    if (php_sapi_name() == 'cli' and empty($_SERVER['REMOTE_ADDR'])) {
        return true;
    } else {
        return false;
    }
}

// put more bootstrapping code here

/**
 * Netbeans Hint
 *
 * Project > Properties > PHPUnit
 * In the Project Properties Dialog
 * 1) Activate Checkbox "Use Bootstrap"
 * 2) Activate Checkbox "Use Bootstrap for Creating New Unit Tests"
 * 3) Use "Browse" and point to this file
 */
