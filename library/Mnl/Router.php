<?php
/**
* Router
*
* @category Mnl
* @package  Mnl
* @author   Markus Nilsson <markus@mnilsson.se>
* @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
* @link     http://mnilsson.se/Mnl
 */

namespace Mnl;

use Mnl\Router\RouteCollection;

class Router
{

    private $controllerPaths;
    private $namespaces;

    private $request;

    private $module;
    private $controller;
    private $action;

    private $params = array();

    private $routes;

    public function __construct()
    {
    }

    public function prepare()
    {
        if ($this->routes !== null) {
            foreach ($this->routes as $routeObject) {
                if ($routeObject->matches($this->request)) {
                    $route = $routeObject->getCompiledRoute($this->request);
                    break;
                }
            }
        }

        if (!isset($route)) {
            throw new Router\NoRouteFoundException("No matching route found");
        }

        $this->module = 'default';

        if (
            isset($route['module'])
            && in_array($route['module'], array_keys($this->controllerPaths))
        ) {
            $this->module = ucwords($route['module']);
        }
        $this->controller = ucwords($route['controller']);

        $this->action = $route['action'];
        $this->params = $route['params'];
    }

    public function run($request)
    {
        if (strpos($request, '?') !== false) {
            $request = substr($request, 0, strpos($request, '?'));
        }
        $this->request = $request;
        $this->prepare();
        $this->deployController();
    }

    public function deployController()
    {
        $controllerFile = strtolower($this->controller).'_controller.php';
        $controllerClassName = $this->controller."Controller";
        if (isset($this->namespaces['default'])) {
            $controllerClassName = $this->namespaces['default'].'\\'.$controllerClassName;
        }

        $action = $this->action;

        try {
            if (
                is_readable(
                    realpath(
                        APPLICATION_PATH.'/'.
                        $this->controllerPaths[strtolower($this->module)].
                        '/'.$controllerFile
                    )
                )
            ) {
                require_once(
                    APPLICATION_PATH.'/'.
                    $this->controllerPaths[strtolower($this->module)].
                    '/'.$controllerFile
                    );
            } else {
                throw new \Mnl\Exception(
                    "Could not find controller: ".$controllerClassName
                );
            }

            if ($this->module != 'default') {
                $controllerClassName = $this->module.'_'.$controllerClassName;
            }

            if (
                !class_exists($controllerClassName)
                && !class_exists(str_replace($this->namespaces['default'].'\\', '', $controllerClassName))
            ) {
                throw new \Mnl\Exception(
                    "Could not find controller: ".$controllerClassName
                );
            } elseif (!class_exists($controllerClassName)) {
                $controllerClassName = str_replace($this->namespaces['default'].'\\', '', $controllerClassName);

            }

            $controller = new $controllerClassName();
            $controller->setParams($this->params);

            if (!is_callable(array($controller,$action))) {
                throw(new \Mnl\Exception("Could not find action: ".$action));
            }
            $controller->setModule($this->module);
            $controller->setControllerName($this->controller);
            $controller->setAction($this->action);

            echo $controller->deploy();

        } catch (\Mnl\Exception $e) {
            throw $e;
        }
    }

    public function isReadable($file)
    {
        if (!$f = @fopen($file, 'r', true)) {
            return false;
        }
        @close($f);

        return true;
    }

    public function setControllerDirectory($directory)
    {
        if (is_array($directory)) {
            $this->controllerPaths = $directory;
        } else {
            $this->controllerPaths['default'] = $directory;
        }
    }

    public function setControllerNamespace($namespace)
    {
        if (is_array($namespace)) {
            $this->namespaces = $namespace;
        } else {
            $this->namespaces['default'] = $namespace;
        }
    }

    public function addControllerDirectory($directory, $moduleName)
    {
        $this->controllerPaths[$moduleName] = $directory;
    }

    public function setRoutes(RouteCollection $routeCollection)
    {
        $this->routes = $routeCollection;
    }

    public static function isXmlHttpRequest()
    {
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            return true;
        } else {
            return false;
        }
    }

    public static function isAjax()
    {
        return self::isXmlHttpRequest();
    }
}
