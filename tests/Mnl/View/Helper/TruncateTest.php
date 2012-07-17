<?php
namespace Test\Mnl\View\Helper;

use Mnl\View\Helper\Truncate;

class TruncateTest extends \PHPUnit_Framework_TestCase
{
    public function testTruncatesString()
    {
        $string = "1234567890";
        $result = Truncate::run(array($string, 5));
        $this->assertEquals("12345", $result);
    }

    public function testDoesntTrucateBelowLimit()
    {
        $string = 'test';
        $result = Truncate::run(array($string, 5));
        $this->assertEquals('test', $result);
    }

    public function testTruncateWithPadding()
    {
        $string = "1234567890";
        $result = Truncate::run(array($string, 5, '...'));
        $this->assertEquals('12345...', $result);
    }

}
