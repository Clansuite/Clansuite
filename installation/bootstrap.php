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
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/**
 * Clansuite Installation Bootstrap
 */

session_start();

@set_time_limit(0);

// Security Handler
define('IN_CS', true);

// Debugging Handler
define('DEBUG', false);

// Define: DS; INSTALLATION_ROOT; ROOT; HTML Break; Carriage Return
define('DS', DIRECTORY_SEPARATOR);
define('INSTALLATION_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT', dirname(INSTALLATION_ROOT) . DIRECTORY_SEPARATOR);
define('ROOT_CACHE', ROOT . 'cache/');
define('ROOT_APP', ROOT . 'application/');
define('PROTOCOL', 'http://', false);
define('SERVER_URL', PROTOCOL . $_SERVER['SERVER_NAME'], false);
define('WWW_ROOT', SERVER_URL . '/application/', false);
define('WWW_ROOT_THEMES_CORE', WWW_ROOT . 'themes/core/');
define('NL', "<br />\r\n", false);
define('CR', "\n");

// load Clansuite Version constants
require ROOT . 'application/version.php';
\Clansuite\Version::setVersionInformation();

// Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', true);
ini_set('display_errors', true);

require ROOT . '/core/exception/exception.php';
require ROOT . '/core/exception/errorhandler.php';
set_exception_handler(array(new \Koch\Exception\Exception,'exception_handler'));

if (DEBUG) {
    echo 'SESSION: ';
    print_r($_SESSION);
    echo 'POST: ';
    print_r($_POST);
}

/**
 * Startup Checks
 */

// PHP Version Check
define('REQUIRED_PHP_VERSION', '5.3.0');
if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<=') === true) {
    throw new Installation_Exception(
            'Your PHP Version is <b>' . PHP_VERSION . '</b>. Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>.', 1);
}

// PDO extension must be available
if (false === class_exists('PDO')) {
    throw new Installation_Exception(
            '"<i>PHP_PDO</i>" extension not enabled. The extension is needed for accessing the database.', 2);
}

// php_pdo_mysql driver must be available
if (false === in_array('mysql', \PDO::getAvailableDrivers())) {
    throw new Installation_Exception(
            '"<i>php_pdo_mysql</i>" driver not enabled. The extension is needed for accessing the database.', 3);
}