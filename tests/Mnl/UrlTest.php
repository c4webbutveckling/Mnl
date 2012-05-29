<?php
class Mnl_UrlTest extends PHPUnit_Framework_TestCase
{
    public function testIsRelativeOnRelativePath()
    {
        $result = Mnl\Url::isRelative("some/relative/path.html");

        $this->assertTrue($result);
    }

    public function testIsRelativeOnAbsolutePath()
    {
        $result = Mnl\Url::isRelative("/some/absolute/path.html");
        $this->assertFalse($result);
    }

    public function testIsRelativeOnFullUrl()
    {
        $result = Mnl\Url::isRelative("http://example.com/some/absolute/path.html");
        $this->assertFalse($result);

        $result = Mnl\Url::isRelative("https://example.com/some/absolute/path.html");
        $this->assertFalse($result);
    }
}
