<?php
class Mnl_InflectorTest extends PHPUnit_Framework_TestCase
{
    protected $_inflector;

    public function setUp()
    {
        $this->_inflector = new Mnl\ActiveRecord\Inflector();
    }

    public function testCamelize()
    {
        $this->assertEquals(
            'testCase',
            $this->_inflector->camelize('test_case')
        );
        $this->assertEquals(
            'testcase',
            $this->_inflector->camelize('testcase')
        );
    }

    public function testUnderscoreize()
    {
        $this->assertEquals(
            'test_case',
            $this->_inflector->underscoreize('TestCase')
        );
        $this->assertEquals(
            'test_case',
            $this->_inflector->underscoreize('Test_Case')
        );
    }

    public function testPluralize()
    {
        $this->assertEquals(
            'testcases',
            $this->_inflector->pluralize('testcase')
        );

        $this->assertEquals(
            'testcases',
            $this->_inflector->pluralize('testcases')
        );
        $this->assertEquals(
            'statuses',
            $this->_inflector->pluralize('status')
        );
    }

    public function testTableize()
    {
         $this->assertEquals(
            'test_cases',
            $this->_inflector->Tableize('TestCase')
        );
    }
}
