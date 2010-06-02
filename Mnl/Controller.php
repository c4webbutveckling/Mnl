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
class Mnl_Controller
{
    private $_action;
    private $_controller;
	
	protected $_view;
    
    protected $_params;

	private $_disableView = false;

    public function __construct()
    {

		$this->_view = new Mnl_View();
		
    }
    
    public function setParams($params = null)
    {
        $this->_params = $params;
    }
    
    public function deploy()
    {
		$view = '';
        $action = $this->_action."Action";
        $this->$action();
		if(!$this->_disableView) {
			return $this->_view->display($this->_controller.'/'.strtolower($this->_action).'.phtml');
		} else {
			return '';
		}
    }
    
	public function setAction($action)
	{
		$this->_action = $action;
	}

	public function setControllerName($controller)
	{
		$this->_controller = $controller;
	}

	
    public function redirect($where)
    {
        $where = BASE_URL.$where;
        header('Location: '.$where);
        exit(); 
    }

	protected function disableView()
	{
		$this->_disableView = true;
	}
}
