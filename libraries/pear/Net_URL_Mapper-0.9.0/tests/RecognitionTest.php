<?php
/**
 * Unit tests for Net_URL_Mapper package
 *
 * PHP version 5
 *
 * LICENSE:
 * 
 * Copyright (c) 2006, Bertrand Mansion <golgote@mamasam.com> 
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the 
 *      documentation and/or other materials provided with the distribution.
 *    * The names of the authors may not be used to endorse or promote products 
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Net
 * @package    Net_URL_Mapper
 * @author     Bertrand Mansion <golgote@mamasam.com>
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Net_URL_Mapper
 */

/**
 * Mapper class
 */
require_once 'Net/URL/Mapper.php';

/**
 * PHPUnit2 Test Case
 */
require_once 'PHPUnit2/Framework/TestCase.php';


/**
 * URL recognition tests for Net_URL_Mapper class
 */
class RecognitionTest extends PHPUnit2_Framework_TestCase
{
    public function testAllFixed()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->connect('hello/world/how/are/you', array('controller'=>'content', 'action'=>'index'));
        $this->assertEquals(null, $m->match('/x'));
        $this->assertEquals(null, $m->match('/hello/world/how'));
        $this->assertEquals(null, $m->match('/hello/world/how/are/you/today'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/hello/world/how/are/you'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/hello/world/how/are/you/'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('hello/world/how/are/you'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('hello/world/how/are/you/'));
    }

    public function testBasicDynamic()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('hi/:name', 'hi/:(name)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'dude'), $m->match('/hi/dude'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'dude'), $m->match('/hi/dude/'));
        }
    }

    public function testBasicDynamicBackwards()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array(':name/hi', ':(name)/hi');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path);
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/shop/here/hi'));
            $this->assertEquals(array('name'=>'fred'), $m->match('/fred/hi'));
            $this->assertEquals(array('name'=>'index'), $m->match('/index/hi'));
        }
    }

    public function testDynamicWithDefault()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('hi/:action', 'hi/:(action)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/hi/index'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'dude'), $m->match('/hi/dude'));
        }
    }

    public function testDynamicWithDefaultBackwards()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array(':action/hi', ':(action)/hi');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/index/hi'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/index/hi/'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'dude'), $m->match('/dude/hi'));
        }
    }

    public function testDynamicWithStringCondition()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('hi/:name', 'hi/:(name)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'), array('name'=>'index'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'index'), $m->match('/hi/index'));
        }
    }

    public function testDynamicWithStringConditionBackwards()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array(':name/hi', ':(name)/hi');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'), array('name'=>'index'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'index'), $m->match('/index/hi'));
        }
    }   

    public function testDynamicWithRegexpCondition()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('hi/:name', 'hi/:(name)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'), array('name'=>'[a-z]+'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(null, $m->match('/hi/dude/what'));
            $this->assertEquals(null, $m->match('/hi/dude/what/'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'index'), $m->match('/hi/index'));
            $this->assertEquals(array('controller'=>'content', 'name'=>'dude'), $m->match('/hi/dude'));
        }
    }

    public function testDynamicAndControllerWithStringAndDefaultBackwards()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array(':controller/:action/hi', ':(controller)/:(action)/hi');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/fred'));
        }
    }

    public function testDefaults()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archive/:year/:month/:day', array('controller'=>'blog', 'action'=>'view', 'month'=>null, 'day'=>null), array('month'=>'\d{1,2}', 'day'=>'\d{1,2}'));

        $this->assertEquals(null, $m->match('/'));
        $this->assertEquals(null, $m->match('/archive'));
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'year'=>'2004', 'month'=>null, 'day'=>null), $m->match('/archive/2004'));
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'year'=>'2004', 'month'=>"10", 'day'=>null), $m->match('/archive/2004/10'));
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'year'=>'2004', 'month'=>"10", 'day'=>"24"), $m->match('/archive/2004/10/24'));

    }

    public function testDefaults2()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archive/:year/:month/:day', array('controller'=>'blog', 'action'=>'view', 'year'=>"2004", 'day'=>null), array('month'=>'\d{1,2}', 'year'=>'\d{4}'));

        $this->assertEquals(null, $m->match('/'));
        $this->assertEquals(null, $m->match('/archive'));
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'year'=>"2004", 'month'=>"10", 'day'=>null), $m->match('/archive/10'));
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'year'=>"2004", 'month'=>"10", 'day'=>"24"), $m->match('/archive/10/24'));

    }

    public function testSplitsWithSlashes()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect(':name/:(action)-:(id)', array('controller'=>'content'));

        $this->assertEquals(null, $m->match('/something'));
        $this->assertEquals(null, $m->match('/something/is'));
        $this->assertEquals(array('controller'=>'content', 'name'=>'group', 'action'=>'view', 'id'=>'3'), $m->match('/group/view-3'));
        $this->assertEquals(null, $m->match('/group/view-'));
        
    }

    public function testSplitsWithSlashesAndDefault()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect(':name/:(action)-:(id)', array('controller'=>'content', 'id'=>null));

        $this->assertEquals(null, $m->match('/something'));
        $this->assertEquals(null, $m->match('/something/is'));
        $this->assertEquals(array('controller'=>'content', 'name'=>'group', 'action'=>'view', 'id'=>'3'), $m->match('/group/view-3'));
        $this->assertEquals(array('controller'=>'content', 'name'=>'group', 'action'=>'view', 'id'=>null), $m->match('/group/view-'));
    }

    public function testSplitsPackedWithRegexes()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archives/:(year):(month):(day).html', array('controller'=>'archive', 'action'=>'view'), array('year'=>'\d{4}', 'month'=>'\d{2}', 'day'=>'\d{2}'));
        $this->assertEquals(null, $m->match('/boo'));
        $this->assertEquals(null, $m->match('/archives'));
        $this->assertEquals(array('controller'=>'archive', 'action'=>'view', 'year'=>'2004', 'month'=>'12', 'day'=>'04'), $m->match('/archives/20041204.html'));
        $this->assertEquals(array('controller'=>'archive', 'action'=>'view', 'year'=>'2005', 'month'=>'10', 'day'=>'04'), $m->match('/archives/20051004.html'));
        $this->assertEquals(array('controller'=>'archive', 'action'=>'view', 'year'=>'2006', 'month'=>'01', 'day'=>'01'), $m->match('/archives/20060101.html'));
    }
    
    public function testDefaultRoute()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('/', array('controller'=>'content', 'action'=>'index'));
        $this->assertEquals(null, $m->match('/boo'));
        $this->assertEquals(null, $m->match('/boo/foo/bar'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'index'), $m->match('/'));
    }

    public function testWildcard()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('hi/*file', 'hi/*(file)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content', 'action'=>'download'));
            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/boo'));
            $this->assertEquals(null, $m->match('/boo/blah'));
            $this->assertEquals(null, $m->match('/hi'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'download', 'file'=>'books/learning_php.pdf'), $m->match('/hi/books/learning_php.pdf'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'download', 'file'=>'dude'), $m->match('/hi/dude'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'download', 'file'=>'dude/what'), $m->match('/hi/dude/what'));
        }
    }

    public function testPathWithDynamicAndDefault()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array(':controller/:action/*url', ':(controller)/:(action)/*(url)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('controller'=>'content', 'action'=>'view', 'url'=>null));

            $this->assertEquals(array('controller'=>'content', 'action'=>'view', 'url'=>null), $m->match('/'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'view', 'url'=>null), $m->match('/content'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'view', 'url'=>null), $m->match('/content/view'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'view', 'url'=>'fred'), $m->match('/content/view/fred'));
            $this->assertEquals(array('controller'=>'content', 'action'=>'view', 'url'=>null), $m->match('/content/view'));
            $this->assertEquals(array('controller'=>'test', 'action'=>'view', 'url'=>null), $m->match('/test'));
            $this->assertEquals(array('controller'=>'test', 'action'=>'view', 'url'=>null), $m->match('/test/view'));
            $this->assertEquals(array('controller'=>'test', 'action'=>'show', 'url'=>null), $m->match('/test/show'));
        }
    }

    public function testPathBackwardsWithControllerAndSplits()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('*(url)/login', array('controller'=>'content', 'action'=>'check_access'));
        $m->connect('*(url)/:(controller)', array('action'=>'view'));

        $this->assertEquals(null, $m->match('/boo'));
        // This test gives a different result from the Python route package since there is no controller concept here
        $this->assertEquals(array('url'=>'boo', 'controller'=>'blah', 'action'=>'view'), $m->match('/boo/blah'));
        $this->assertEquals(null, $m->match('/login'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'check_access', 'url'=>'books/learning_php.pdf'), $m->match('/books/learning_php.pdf/login'));
    }


    public function testDynamicWithRegexpGapsControllers()
    {
        $m = Net_URL_Mapper::getInstance();
        $paths = array('view/:id/:controller', 'view/:(id)/:(controller)');
        foreach ($paths as $path) {
            $m->reset();
            $m->connect($path, array('id'=>'2', 'action'=>'view'), array('id'=>'\d{1,2}', 'controller'=>'(blog|test)'));

            $this->assertEquals(null, $m->match('/'));
            $this->assertEquals(null, $m->match('/view'));
            $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'id'=>'2'), $m->match('/view/blog'));
            $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'id'=>'2'), $m->match('/view/2/blog'));
        }
    }    

    public function testUrlWithPrefix()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->setPrefix('/en');
        $m->connect(':controller/:action/:id');
        $this->assertEquals(array('controller'=>'blog', 'action'=>'view', 'id'=>'2'), $m->match('/en/blog/view/2'));
        $this->assertEquals(null, $m->match('/blog/view/2'));
    }

    public function testQstring()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('*(url)/login', array('controller'=>'content', 'action'=>'check_access'));
        $m->connect('*(url)/:(controller)', array('action'=>'view'));

        $this->assertEquals(null, $m->match('/boo?test=1'));
        $this->assertEquals(array('url'=>'boo', 'controller'=>'blah', 'action'=>'view'), $m->match('/boo/blah?test=1'));
        $this->assertEquals(null, $m->match('/login?test=1'));
        $this->assertEquals(array('controller'=>'content', 'action'=>'check_access', 'url'=>'books/learning_php.pdf'), $m->match('/books/learning_php.pdf/login?test=1'));
    }
    
    public function testSharp()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('/account/:action/:handle*(section)',
            array('module'=>'account', 'section'=>'#default'),
            array('handle'=>'[a-zA-Z0-9]{3,12}',
                  'section'=>'#\w+'));

        $this->assertEquals(
            array('module'=>'account',
                'action'=>'edit',
                'handle'=>'mansion',
                'section'=>'#password'), $m->match('/account/edit/mansion#password'));
        $this->assertEquals(
            array('module'=>'account',
                'action'=>'edit',
                'handle'=>'mansion',
                'section'=>'#default'), $m->match('/account/edit/mansion'));
    }

}






















?>