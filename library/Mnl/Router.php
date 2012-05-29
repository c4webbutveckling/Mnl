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
            throw new Exception("No matching route found");
        }

        $this->module = 'default';

        if(
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

            if (!class_exists($controllerClassName)) {
                throw new \Mnl\Exception(
                    "Could not find controller: ".$controllerClassName
                    );
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

    public function getController($controller, $file, $action, $args )
    {
        $route = $_GET['route'];
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        $fullpath = 'application/';

        foreach ($parts as $part) {
            if (is_dir($fullpath.$part)) {
                $fullpath .=$part."/";
                array_shift($parts);
            }

            if (is_file($fullpath.$part.'_controller.php')) {
                $controller = $part.'_controller';
                array_shift($parts);
                break;
            }
        }

        //if (empty($controller)) {
            //$controller = $this->registry->settings->DEFAULT_CONTROLLER;
            //if (is_dir($fullpath.$controller)) {
                //$fullpath .= $controller."/";
            //}
            //$controller .= '_controller';
        //}

        $file = $fullpath.$controller.'.php';

        $action = array_shift($parts);
        if (empty($action)) {
            $action = 'index';
        }

        $args = $parts;
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

    public function addControllerDirectory($directory, $moduleName)
    {
        $this->controllerPaths[$moduleName] = $directory;
    }

    public function setRoutes(RouteCollection $routeCollection)
    {
        $this->routes = $routeCollection;
    }
}
