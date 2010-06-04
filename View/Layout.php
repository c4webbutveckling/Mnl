<?php
/**
* Mnl_Layout
*
* @category Mnl
* @package  Mnl_View
* @author   Markus Nilsson <markus@mnilsson.se>
* @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
* @link     http://mnilsson.se/Mnl
*/
class Mnl_View_Layout
{
    protected $_name;
    protected $_vars;
    protected $_enabled = true;

    protected $_viewContent;

    protected static $_instance = null;

    public static function getLayout()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function setViewContent($content)
    {
        $this->_viewContent = $content;
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

    public function fetch($file = 'layout.phtml')
    {

        $layout = new Mnl_View();
        $layout->assign($this->_vars);
        $layoutResult = $layout->fetch($file);
        $layoutResult = str_replace(
            "{content}", 
            $this->_viewContent, 
            $layoutResult
        );
        return $layoutResult;
    }

    public function isEnabled()
    {
        return $this->_enabled;
    }
}