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
require_once 'PHPUnit2/Extensions/ExceptionTestCase.php';

/**
 * URL recognition tests for Net_URL_Mapper class
 */
class ExceptionTest extends PHPUnit2_Extensions_ExceptionTestCase
{
    public function setup()
    {
        $this->setExpectedException('Net_URL_Mapper_InvalidException');
    }

    public function testDynamicWithStringCondition()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('hi/:name', array('controller'=>'content'), array('name'=>'index'));
        $m->match('/hi/dude');
    }

    public function testDynamicWithStringConditionBackwards()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect(':name/hi', array('controller'=>'content'), array('name'=>'index'));
        $v = $m->match('/dude/hi');
    }   

    public function testDynamicWithRegexpCondition1()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('hi/:name', array('controller'=>'content'), array('name'=>'[a-z]+'));
        $m->match('/hi/FOXY');
    }

    public function testDynamicWithRegexpCondition2()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('hi/:name', array('controller'=>'content'), array('name'=>'[a-z]+'));
        $m->match('/hi/1234abcd');
    }

    public function testDynamicWithRegexpCondition3()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('hi/:name', array('controller'=>'content'), array('name'=>'[a-z]+'));
        $m->match('/hi/abc123cdf');
    }

    public function testMultiroute1()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archive/:year/:month/:day', array('controller'=>'blog', 'action'=>'view', 'month'=>null, 'day'=>null), array('month'=>'\d{1,2}', 'day'=>'\d{1,2}'));
        $m->match('/archive/2004/ab');
    }

    public function testMultiroute2()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archive/:year/:month/:day', array('controller'=>'blog', 'action'=>'view', 'month'=>null, 'day'=>null), array('month'=>'\d{1,2}', 'day'=>'\d{1,2}'));
        $m->match('/archive/2004/10/ab');
    }

    public function testDefaults2()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archive/:year/:month/:day', array('controller'=>'blog', 'action'=>'view', 'year'=>null, 'day'=>null), array('month'=>'\d{1,2}', 'day'=>'\d{1,2}'));
        $this->assertEquals(null, $m->match('/archive/2004'));
    }

    public function testSplitsWithSlashes()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect(':name/:(action)-:(id)', array('controller'=>'content'), array('id'=>'\d{1,2}'));
        $m->match('/group/view-356');
    }

    public function testSplitsPackedWithRegexes()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('archives/:(year):(month):(day).html', array('controller'=>'archive', 'action'=>'view'), array('year'=>'\d{4}', 'month'=>'\d{2}', 'day'=>'\d{2}'));
        $m->match('/archives/2004120.html');
    }

    public function testDynamicWithRegexpGapsControllers1()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('view/:id/:controller', array('id'=>'2', 'action'=>'view'), array('id'=>'\d{1,2}', 'controller'=>'(blog|test)'));
        $this->assertEquals(null, $m->match('/view/3'));
            //$this->assertEquals(null, $m->match('/view/4/honker'));
    }

    public function testDynamicWithRegexpGapsControllers2()
    {
        $m = Net_URL_Mapper::getInstance();
        $m->reset();
        $m->connect('view/:id/:controller', array('id'=>'2', 'action'=>'view'), array('id'=>'\d{1,2}', 'controller'=>'(blog|test)'));
        $this->assertEquals(null, $m->match('/view/4/honker'));
    }
}
?>