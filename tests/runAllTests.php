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

define('CODECOVERAGE', false);

# Coverage -> xdebug is needed for code coverage
if (extension_loaded('xdebug') and function_exists("xdebug_start_code_coverage") and CODECOVERAGE == true)
{
    # ensure that the sqlite extension is loaded
    #require_once 'simpletest/extensions/coverage/coverage_utils.php';
    #CoverageUtils::requireSqliteExtension();

    # setup code coverage
    require_once 'simpletest/extensions/coverage/coverage.php';
    $coverage = new CodeCoverage();
    $coverage->log = 'coverage.sqlite';
    $coverage->root = TESTSUBJECT_DIR;
    $coverage->includes[] = '.*\.php$';
    $coverage->excludes[] = 'simpletest';
    $coverage->excludes[] = 'tests';
    $coverage->excludes[] = 'libraries';
    $coverage->excludes[] = 'coverage-report';
    $coverage->excludes[] = 'sweety';
    $coverage->excludes[] = './.*.php';
    $coverage->maxDirectoryDepth = 1;
    $coverage->resetLog();
    $coverage->writeSettings();

    # this starts the code coverage
    $coverage->startCoverage();
    #echo 'Code Coverage started...';
}

# Tests -> instantiate Clansuite Testsuite
$testsuite = new ClansuiteTestsuite();
$success = false;

# Tests -> determine, if we are in commandline mode, then output pure text
if(TextReporter::inCli())
{
    #require_once 'simpletest/extensions/colortext_reporter.php';
    require_once 'simpletest/extensions/junit_xml_reporter.php';
    ob_start();
    #$reporter = new TextReporter();
    #$reporter = new ColorTextReporter();
    $reporter = new JUnitXmlReporter();
    $success = $testsuite->run($reporter);
    file_put_contents(__DIR__ . '/test-results.xml', ob_get_contents());
    ob_end_clean();
    $success = true;
}
else # else display nice html report
{
    $success = $testsuite->run(new Reporter);
}

if(CODECOVERAGE == true)
{
    # Coverage -> close coverage
    $coverage->writeUntouched();
    $coverage->stopCoverage();
    #echo 'Code Coverage stopped!';

    # Coverage -> generate report
    require_once 'simpletest/extensions/coverage/coverage_reporter.php';
    $handler = new CoverageDataHandler($coverage->log);
    $report = new CoverageReporter();
    $report->reportDir = 'coverage-report';
    $report->title = 'Simpletest Coverage Report';
    $report->coverage = $handler->read();
    $report->untouched = $handler->readUntouchedFiles();
    $report->generate();
}

# Tests -> exit with status
if(false === $success)
{
    # Exit with error code to let the build fail, when the test is unsuccessfull.
    exit(1);
}
?>