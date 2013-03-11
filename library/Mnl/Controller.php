<?php
/**
 * Controller
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl;

/**
 * Controller
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Controller
{

    /**
     * Module name
     *
     * @var string $module
     */
    private $module;

    /**
     * Action name
     *
     * @var string $action
     */
    private $action;

    /**
     * Controller name
     *
     * @var string $controller
     */
    private $controller;

    /**
     * Disable view flag
     *
     * @var boolean $disableView
     */
    private $disableView;

    /**
     * View object
     *
     * @var \Mnl\View $view
     */
    protected $view;

    /**
     * Parameters for action
     *
     * @var array $params
     */
    protected $params;

    /**
     * Layout file name
     *
     * @var string $layoutFile
     */
    protected $layoutFile;

    /**
     * Initialize new Controller object
     */
    public function __construct()
    {
        $this->view = new View();
        $this->disableView = false;
    }

    /**
     * Set parameters for action
     *
     * @param array $params
     */
    public function setParams($params = null)
    {
        $this->params = $params;
    }

    /**
     * Run controller and load file
     *
     * @return string Result of view rendering
     */
    public function deploy()
    {
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

    /**
     * Set module name
     *
     * @param string $module Module name
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Set action to run
     *
     * @param string $action Action name
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action to run
     *
     * @return string $action Action name
     */
    public function getActionName()
    {
        return $this->action;
    }

    /**
     * Set controller name
     *
     * @param string $controller Controller name
     */
    public function setControllerName($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get controller name
     *
     * @return string Controller name
     */
    public function getControllerName()
    {
        return $this->controller;
    }

    /**
     * Redirect internally
     *
     * @param string $where Internal url relative to root
     */
    public function redirect($where)
    {
        $where = BASE_URL.$where;
        header('Location: '.$where);
        exit();
    }

    /**
     * Set view file to use this request
     *
     * @param string $viewFile File path relative to the template directory
     */
    public function setViewFile($viewFile)
    {
        $this->viewFile = $viewFile;
    }

    /**
     * Set layout file to use this request
     *
     * @param string $layoutFile File path relative to the template directory
     */
    protected function setLayoutFile($layoutFile)
    {
        $this->layoutFile = $layoutFile;
    }

    /**
     * Disable view rendering
     */
    protected function disableView()
    {
        $this->disableView = true;
    }

    /**
     * Disable layout rendering
     */
    protected function disableLayout()
    {
        \Mnl\View\Layout::getLayout()->disable();
    }

    /**
     * Runs before action
     */
    public function before()
    {
    }

    /**
     * Runs after action
     */
    public function after()
    {
    }
}
