<?php
class Mnl_RegistryTest extends PHPUnit_Framework_TestCase
{
    protected $registry;

    public function setUp()
    {
        $this->registry = Mnl\Registry::getInstance();
        $this->registry->testKey = 'testValue';
    }

    public function testGetInstance()
    {
        $instance = Mnl\Registry::getInstance();
        $this->assertEquals($this->registry, $instance);
    }


    public function testGetExistingValue()
    {
        $value = $this->registry->testKey;
        $this->assertEquals('testValue', $value);
    }

    public function testGetNonExistingValue()
    {
        $result = $this->registry->nonExisting;
        $this->assertFalse($result);
    }

    public function testMagicSetValue()
    {
        $this->registry->magicKey = 'magicValue';
        $value = $this->registry->magicKey;
        $this->assertEquals('magicValue', $value);
    }

    public function testSetValue()
    {
        $this->registry->set('setKey', 'setValue');
        $value = $this->registry->setKey;
        $this->assertEquals('setValue', $value);
    }
}

