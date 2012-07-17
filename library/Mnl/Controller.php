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

    private $module;
    private $action;
    private $controller;
    private $disableView;

    protected $view;

    protected $params;

    protected $layoutFile;

    public function __construct()
    {
        $this->view = new View();
        $this->disableView = false;
    }

    public function setParams($params = null)
    {
        $this->params = $params;
    }

    public function deploy()
    {
        $view = '';
        $action = $this->action;

        $this->before();
        call_user_func_array(array($this, $this->action), $this->params);
        $this->after();

        if (isset($this->layoutFile)) {
            $layoutFile = $this->layoutFile;
        } else {
            $layoutFile = 'layout.phtml';
        }
        if (!$this->disableView) {
            if (isset($this->viewFile) && $this->viewFile != '') {
                return $this->view->render($this->viewFile, $layoutFile);
            } else {
                $viewFile = $this->controller.'/'.$this->action.'.phtml';
                if ($this->module != 'default') {
                    $viewFile = $this->module.'/'.$viewFile;
                }

                $viewFile = strtolower($viewFile);

                return $this->view->render(
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
        $this->action = $action;
    }

    public function getActionName()
    {
        return $this->action;
    }

    public function setControllerName($controller)
    {
        $this->controller = $controller;
    }

    public function getControllerName()
    {
        return $this->controller;
    }

    public function redirect($where)
    {
        $where = BASE_URL.$where;
        header('Location: '.$where);
        exit();
    }

    public function setViewFile($viewFile)
    {
        $this->viewFile = $viewFile;
    }

    protected function setLayoutFile($layoutFile)
    {
        $this->layoutFile = $layoutFile;
    }

    protected function disableView()
    {
        $this->disableView = true;
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
