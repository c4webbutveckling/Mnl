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
    private $_request;
    private $_controller;
    private $_action;
    private $_module;

    private $_params = array();

    function __construct()
    {
        $this->getRequest();
        $this->prepare();
        $this->run();
    }

    public function prepare()
    {
        $route = explode('/', $this->_request);

        if (isset($route[0]) && $route[0] != '') {
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
            $this->_params[$route[0]] = $route[1];
            array_shift($route);
            array_shift($route);
        }
    }

    function run()
    {
        $controller = $this->_controller."Controller";
        $action = $this->_action."Action";

        try {
            if (
                is_readable(
                    APPLICATION_PATH.'/'.
                    Mnl_Registry::getInstance()->defaultControllerPath.
                    '/'.$controller.'.php'
                )
            ) {
                require_once(
                    APPLICATION_PATH.'/'.
                    Mnl_Registry::getInstance()->defaultControllerPath.
                    '/'.$controller.'.php'
                    );
            } else {
                throw(new Mnl_Exception(
                    "Could not find controller: ".$controller
                    ));
            }

            if (!class_exists($controller)) {
                throw(new Mnl_Exception(
                    "Could not find controller: ".$controller
                    ));
            }

            $controller = new $controller();
            $controller->setParams($this->params);

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
        $this->request = @$_GET['route'];
    }

    private function isReadable($file)
    {
        if (!$f = @fopen($file, 'r', true)) {
            return false;
        }
        @close($f);
        return true;
    }
}
