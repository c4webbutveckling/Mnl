<?php
use Mnl\Router\Route;
class Mnl_Router_RouterTest extends PHPUnit_Framework_TestCase
{
    public function testMatchesControllerAndParam()
    {
        $route = new Mnl\Router\Route('test/(:num)', 'test#view?id=$1');
        $this->assertTrue($route->matches('/test/7'));
    }

    public function testMapsControllerAndParamToControllerAction()
    {
        $expected = array(
            'controller' => 'test',
            'action' => 'view',
            'params' => array('id' => 7)
        );
        $route = new Mnl\Router\Route('test/(:num)', 'test#view?id=$1');
        $this->assertEquals($route->getCompiledRoute('/test/7'), $expected);
    }

    public function testMatchesExactControllerAction()
    {
        $route = new Route('test/view', 'test#view');
        $this->assertTrue($route->matches('/test/view/'));
    }

    public function testMapsExactControllerAction()
    {
        $expected = array(
            'controller' => 'test',
            'action' => 'view',
            'params' => array()
        );
        $route = new Route('test/view', 'test#view');
        $this->assertEquals($route->getCompiledRoute('/test/view/'), $expected);
    }

}
