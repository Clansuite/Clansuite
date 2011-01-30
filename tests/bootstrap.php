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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com

    *
    * @version    SVN: $Id$
    */

# Set Security Handler
define('IN_CS', true);

# Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

# Tests take some time
set_time_limit(0);

date_default_timezone_set('Europe/Berlin');

define('TESTSUBJECT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);   # /trunk

$paths = array(
        # adjust include path to SIMPLETEST DIR and UNITTESTS DIR
        realpath(__DIR__),                 # /trunk/tests
        realpath(__DIR__.'/simpletest'),   # /trunk/tests/simpletest
        realpath(__DIR__.'/unittests'),    # /trunk/tests/unittests
        # add the test subject dir
        realpath(dirname(__DIR__)) . '/',        # /trunk
        /**
         * Zend Server Paths
         * resides at = C:\Programme\Zend\
         * Test are at = C:\Programme\Zend\Apache2\htdocs\clansuite\trunk\tests
         */
        realpath(dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/ZendServer/bin'),
        realpath(dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/ZendServer/bin/PEAR')
);
#var_dump($paths);

# attach original include paths
set_include_path(implode($paths, PATH_SEPARATOR) . PATH_SEPARATOR . get_include_path());
unset($paths);

#  acquire clansuite path constants
require_once '../core/bootstrap/clansuite.application.php';
Clansuite_CMS::initialize_Paths();

# put more bootstrapping code here

/**
 * Netbeans Hint
 *
 * Project > Properties > PHPUnit
 * In the Project Properties Dialog
 * 1) Activate Checkbox "Use Bootstrap"
 * 2) Activate Checkbox "Use Bootstrap for Creating New Unit Tests"
 * 3) Use "Browse" and point to this file
 */
?>