<?php
/**
 * Mnl_Controller
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl;

class Controller
{

    public $module;
    private $_action;
    private $_controller;

    protected $_view;


    protected $_params;

    private $_disableView = false;

    public function __construct()
    {
        $this->_view = new View();
    }
    
    public function setParams($params = null)
    {
        $this->_params = $params;
    }
    
    public function deploy()
    {
        $view = '';
        $action = $this->_action;

        $this->before();
        call_user_func_array(array($this, $action), $this->_params);
        $this->after();
        if (isset($this->_layoutFile)) {
            $layoutFile = $this->_layoutFile;
        } else {
            $layoutFile = 'layout.phtml';
        }
        if (!$this->_disableView) {
            if (isset($this->_viewFile) && $this->_viewFile != '') {
                return $this->_view->display($this->_viewFile, $layoutFile);
            } else {
                $viewFile = strtolower($this->_controller).'/'.strtolower($this->_action).'.phtml';
                if ($this->module != 'default') {
                    $viewFile = strtolower($this->module).'/'.$viewFile;
                }
                return $this->_view->display(
                    $viewFile,
                    $layoutFile
                );
            }
        } else {
            return '';
        }
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getActionName()
    {
        return $this->_action;
    }

    public function setControllerName($controller)
    {
        $this->_controller = $controller;
    }

    public function getControllerName()
    {
        return $this->_controller;
    }

    public function redirect($where)
    {
        $where = BASE_URL.$where;
        header('Location: '.$where);
        exit(); 
    }

    public function setViewFile($viewFile)
    {
        $this->_viewFile = $viewFile;
    }

    protected function setLayoutFile($layoutFile)
    {
        $this->_layoutFile = $layoutFile;
    }

    protected function disableView()
    {
        $this->_disableView = true;
    }

    /**
     * Runs before action
     */
    public function before()
    {}

    /**
     * Runs after action
     */
    public function after()
    {}
}
