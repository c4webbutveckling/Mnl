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
namespace Mnl;
class View
{
    private static $_templateDirectory;
    private $_vars;

    public function __construct()
    {
        if (self::$_templateDirectory == null) {
            throw new Exception('Template directory not set');
        }
        $this->_vars = array();
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
        extract($this->_vars);

        ob_start();
        if (file_exists(self::$_templateDirectory.'/'.$file)) {
            include(self::$_templateDirectory.'/'.$file);
        } else {
            throw new Exception("View file `".$file."` not found");
        }
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }

    public function display($file, $layoutFile = 'layout.phtml')
    {
        $layout = View\Layout::getLayout();
        if ($layout->isEnabled()) {
            $this->assign($layout->getVars());
            $view = $this->fetch($file);
            $layout->setViewContent($view);
            $result = $layout->fetch($layoutFile);
        } else {
            $result = $this->fetch($file);
        }
        echo $result;
    }

    public function __call($name, $args)
    {
        $helper = View\Helper\Loader::load($name);
        return call_user_func_array(
            array($helper, 'run'),
            array($args)
        );
    }

    public static function setTemplateDirectory($path)
    {
        if (is_dir($path)) {
            self::$_templateDirectory = $path;
        }
    }
}
