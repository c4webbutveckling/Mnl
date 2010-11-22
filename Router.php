<?php 
/**
* Mnl_Router
*
* @category Mnl
* @package  Mnl
* @author   Markus Nilsson <markus@mnilsson.se>
* @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
* @link     http://mnilsson.se/Mnl
*/
class Mnl_Router
{

    public $controllerPaths;

    public $module;

    private $_request;
    private $_controller;
    private $_action;
    private $_module;

    private $_params = array();

    function __construct()
    {
    }

    public function prepare()
    {
        $route = explode('/', $this->_request);

        $this->module = 'default';

        if (isset($route[0]) && $route[0] != '') {
            if(in_array($route[0], array_keys($this->controllerPaths))) {
                $this->module = ucwords($route[0]);
                array_shift($route);
            }
            $this->_controller = $route[0];
            array_shift($route);
        } else {
            $this->_controller = defaultController;
        }

        if (isset($route[0]) && $route[0] != "") {
            $this->_action = $route[0];
            array_shift($route);
        } else {
            $this->_action = defaultAction;
        }

        while (isset($route[0]) && $route[0] != '') {
            if(isset($route[1])) {
                $this->_params[$route[0]] = $route[1];
            } else {
                $this->_params[$route[0]] = '';
            }
            array_shift($route);
            array_shift($route);
        }
    }

    function run()
    {
        $this->getRequest();
        $this->prepare();
        $this->deployController();
    }

    public function deployController()
    {
        $controller = $this->_controller."Controller";
        $action = $this->_action."Action";

        try {
            if (
                is_readable(
                    APPLICATION_PATH.'/'.
                    $this->controllerPaths[strtolower($this->module)].
                    '/'.$controller.'.php'
                )
            ) {
                require_once(
                    APPLICATION_PATH.'/'.
                    $this->controllerPaths[strtolower($this->module)].
                    '/'.$controller.'.php'
                    );
            } else {
                throw(new Mnl_Exception(
                    "Could not find controller: ".$controller
                    ));
            }

            if ($this->module != 'default') {
                $controller = $this->module.'_'.$controller;
            }

            if (!class_exists($controller)) {
                throw(new Mnl_Exception(
                    "Could not find controller: ".$controller
                    ));
            }

            $controller = new $controller();
            $controller->setParams($this->_params);

            if (!is_callable(array($controller,$action))) {
                throw(new Mnl_Exception("Could not find action: ".$action));
            }
            $controller->setControllerName($this->_controller);
            $controller->setAction($this->_action);

            echo $controller->deploy();

        } catch (Mnl_Exception $e) {
            echo "Exception: '".$e->getMessage()."'";
        }
    }

    function getController($controller, $file, $action, $args )
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

        if (empty($controller)) {
            $controller = $this->registry->settings->DEFAULT_CONTROLLER;
            if (is_dir($fullpath.$controller)) {
                $fullpath .= $controller."/";
            }
            $controller .= '_controller';
        }

        $file = $fullpath.$controller.'.php';

        $action = array_shift($parts);
        if (empty($action)) {
            $action = 'index';
        }

        $args = $parts;
    }

    private function getRequest()
    {
        $this->_request = @$_GET['route'];
    }

    private function isReadable($file)
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
}
