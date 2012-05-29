<?php
namespace Mnl\Router;

class RouteCollection implements \IteratorAggregate
{
    private $routes;

    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    public function add($name, Route $route)
    {
        $this->routes[$name] = $route;
    }

    public function all()
    {
        return $this->routes;
    }

}
