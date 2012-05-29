<?php

namespace Mnl\Router;

class Route
{
    private $pattern;
    private $parameters;
    private $target;

    public function __construct($pattern, $target)
    {
        $this->setPattern($pattern);
        $this->setTarget($target);
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }


    public function parsePattern()
    {
        $parsedPattern = str_replace(
            array(':any', ':num'),
            array('.+', '[0-9]+'),
            $this->getPattern()
        );

        return $parsedPattern;
    }

    public function matches($uri)
    {
        $uri = trim($uri, '/');
        return (bool)preg_match('#^'.$this->parsePattern().'$#', $uri);
    }

    public function getCompiledRoute($uri)
    {
        $uri = trim($uri, '/');
        $route = preg_replace(
            '#^'.$this->parsePattern().'$#',
            $this->getTarget(),
            $uri
        );

        if (strpos($route, '?') !== false) {
            $rawParamString = explode('?', $route);
            $route = $rawParamString[0];
            parse_str($rawParamString[1], $params);
        }
        $route = explode('#', $route);
        if (count($route) == 3) {
            $module = array_shift($route);
        }
        $compiledRoute = array(
            'controller' => $route[0],
            'action' => isset($route[1]) ? $route[1] : '',
            'params' => isset($params) ? $params: array()
        );
        if (isset($module)) {
            $compiledRoute['module'] = $module;
        }
        return $compiledRoute;
    }
}
