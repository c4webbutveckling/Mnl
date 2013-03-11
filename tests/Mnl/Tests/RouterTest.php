<?php
namespace Mnl\Tests;

use Mnl;
class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $router = new Mnl\Router();
    }

    /**
     * @expectedException Mnl\Router\NoRouteFoundException
     */
    public function testNoRouteFound()
    {
        $router = new Mnl\Router();
        $router->run("/no_route_to_this");
    }
}
