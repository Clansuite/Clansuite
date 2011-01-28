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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# setup env(error/date/paths)
require_once 'bootstrap.php';
# setup simpletest
require_once 'simpletest/unit_tester.php';
require_once 'simpletest/test_case.php';
require_once 'simpletest/reporter.php';
# setup our testsuite and reporter
require_once 'reporter.php';
require_once 'testsuite.php';

# instantiate Clansuite Testsuite
$testsuite = new ClansuiteTestsuite();
$success = false;

# determine, if we are in commandline mode, then output pure text
if(TextReporter::inCli())
{
    #require_once 'simpletest/extensions/colortext_reporter.php';
    require_once 'simpletest/extensions/junit_xml_reporter.php';
    ob_start();
    #$reporter = new TextReporter();
    #$reporter = new ColorTextReporter();
    $reporter = new JUnit_Xml_Reporter();
    $success = $testsuite->run($reporter);
    file_put_contents('simpletest.xml', ob_get_contents());
    ob_end_clean();
    $success = true;
}
else # else display nice html report
{
    $success = $testsuite->run(new Reporter);
}

if(false === $success)
{
    # Exit with error code to let the build fail, when the test is unsuccessfull.
    exit(1);
}
?>