<?php
namespace Mnl\Tests;

use Mnl\Router\RouteCollection;
use Mnl\Router\Route;
class Mnl_Router_RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRoute()
    {
        $collection = new RouteCollection();
        $route = new Route('/test', '/test');
        $collection->add('test', $route);
        $this->assertEquals(array('test' => $route), $collection->all());
    }

    public function testOverrideRoute()
    {
        $collection = new RouteCollection();
        $route1 = new Route('/test', '/test');
        $collection->add('test', $route1);

        $route2 = new Route('/foo', '/foo');
        $collection->add('test', $route2);
        $this->assertEquals(array('test' => $route2), $collection->all());
    }
}
