<?php
/**
 * Mnl_View
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_View
{
    private $_vars = array();

    public function __construct()
    {
    }

    public function __get($key)
    {
        if (isset($this->_vars['key'])) {
            return $this->_vars['key'];
        } else {
            return null;
        }
    }

    public function __set($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    public function assign($key, $value = null)
    {
        if (is_string($key)) {
            $this->_vars[$key] = $value;
        } else if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->_vars[$k] = $v;
            }
        }
    }

    public function fetch($file)
    {
        foreach ($this->_vars as $key => $val) {
            $$key = $val;
        }
        ob_start();
        include(Mnl_Registry::getInstance()->templatePath.
            '/'.$file);
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }

    public function display($file)
    {
        $layout = Mnl_View_Layout::getLayout();
        if ($layout->isEnabled()) {
            $view = $this->fetch($file);
            $layout->setViewContent($view);
            $result = $layout->fetch();
        } else {
            return $this->fetch($file);
        }
        echo $result;
    }

    public function __call($name, $args)
    {
        $helper = Mnl_View_Helper_Loader::load($name);
        return call_user_func_array(
            array($helper, 'run'),
            array($args)
        );
    }
}
