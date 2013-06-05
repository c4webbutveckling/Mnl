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

    public function getRouteByName($name)
    {
        if (isset($this->routes[$name])) {
            return $this->routes[$name];
        } else {
            return new Route('', '');
        }
    }
}
