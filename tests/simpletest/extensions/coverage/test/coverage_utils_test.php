<?php
require_once dirname(__FILE__) . '/../../../autorun.php';

class CoverageUtilsTest extends UnitTestCase
{
    public function setUp()
    {
        require_once dirname(__FILE__) .'/../coverage_utils.php';
    }

    public function testMkdir()
    {
        CoverageUtils::mkdir(dirname(__FILE__));
        try {
            CoverageUtils::mkdir(__FILE__);
            $this->fail("Should give error about cannot create dir of a file");
        } catch (Exception $expected) {
        }
    }

    public function testParseArgumentsMultiValue()
    {
        $actual = CoverageUtils::parseArguments(array('scriptname', '--a=b', '--a=c'), True);
        $expected = array('extraArguments' => array(), 'a' => 'c', 'a[]' => array('b', 'c'));
        $this->assertEqual($expected, $actual);
    }

    public function testParseArguments()
    {
        $actual = CoverageUtils::parseArguments(array('scriptname', '--a=b', '-c', 'xxx'));
        $expected = array('a' => 'b', 'c' => '', 'extraArguments' => array('xxx'));
        $this->assertEqual($expected, $actual);
    }

    public function testParseDoubleDashNoArguments()
    {
        $actual = CoverageUtils::parseArguments(array('scriptname', '--aa'));
        $this->assertTrue(isset($actual['aa']));
    }

    public function testParseHyphenedExtraArguments()
    {
        $actual = CoverageUtils::parseArguments(array('scriptname', '--alpha-beta=b', 'gamma-lambda'));
        $expected = array('alpha-beta' => 'b', 'extraArguments' => array('gamma-lambda'));
        $this->assertEqual($expected, $actual);
    }

    public function testAddItemAsArray()
    {
        $actual = array();
        CoverageUtils::addItemAsArray($actual, 'bird', 'duck');
        $this->assertEqual(array('bird[]' => array('duck')), $actual);

        CoverageUtils::addItemAsArray(&$actual, 'bird', 'pigeon');
        $this->assertEqual(array('bird[]' => array('duck', 'pigeon')), $actual);
    }

    public function testIssetOr()
    {
        $data = array('bird' => 'gull');
        $this->assertEqual('lab', CoverageUtils::issetOr($data['dog'], 'lab'));
        $this->assertEqual('gull', CoverageUtils::issetOr($data['bird'], 'sparrow'));
    }
}
