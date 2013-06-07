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
}
