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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# setup simpletest
require_once 'bootstrap.php';
#require_once 'simpletest/autorun.php';
require_once 'simpletest/unit_tester.php';
require_once 'simpletest/reporter.php';

function scanDirForTests($dir)
{
    $ret = array();
    if(is_dir($dir))
    {
        #var_dump($dir);
        $sourcedir = opendir($dir);
        while(false !== ( $file = readdir($sourcedir) ))
        {
            $source_file = $dir . '/' . $file;
            $source_file = str_replace('//', '/', $source_file);
            if(is_dir($source_file))
            {
                if($file == '.' || $file == '..' || $file == '.svn')
                {
                    continue;
                }
                scanDirForTests($source_file);
            }
            elseif(eregi('(.*)\.test\.php', $file))
            {
                # add file to array
                $ret[] = $source_file;
                #echo "<p>File {$source_file} was added to the tests array.</p>\n";
            }
        }
        closedir($sourcedir);
    }
    return $ret;
}

class ClansuiteTestsuite extends TestSuite
{

    function __construct()
    {
        # add a headline to know where we are ,)
        $this->TestSuite('Testsuite for "Clansuite - just an eSports CMS"');

        # walk through dir /unittests and grab all tests
        $tests = scanDirForTests(dirname(__FILE__) . '/unittests/');

        # @todo check array structure of $tests an add Grouping by directory
        # $test = new GroupTest('GroupTest for /core of Clansuite');
        # $tests = array_merge($tests);
        # var_dump($tests);

        if(count($tests) > 0)
        {
            foreach($tests as $testfile)
            {
                $this->addFile($testfile);
            }
        }
    }

}

# instantiate ClansuiteTestsuite
$testsuite = new ClansuiteTestsuite;

# determine, if we are in commandline mode, then output pure text
if(TextReporter::inCli())
{
    $success = $testsuite->run(new TextReporter());
}
else # else display nice html report
{
    $success = $testsuite->run(new HtmlReporter());
}

if(false == $success)
{
    # Exit with error code to let the build fail, when the test is unsuccessfull.
    exit(1);
}
?>