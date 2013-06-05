<?php
namespace Mnl\Tests;

use Mnl;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        new Mnl\Router();
    }

    /**
     * @expectedException Mnl\Router\NoRouteFoundException
     */
    public function testNoRouteFound()
    {
        $router = new Mnl\Router();
        $router->setRequest("/no_route_to_this");
        $router->prepare();
    }

    public function test404StatusWhenNoRouteFound()
    {
        $router = new Mnl\Router();
        $response = $router->run("/no_route_to_this");
        $this->assertEquals(404, $response->getStatusCode());
    }
}
