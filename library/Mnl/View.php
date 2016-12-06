<?php
/**
 * Mnl\View
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl;

/**
 * Mnl\View
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class View
{
    /**
     * Directory containing templates
     * @var string $templateDirectory
     */
    protected static $templateDirectory;

    /**
     * Array containgin assigned variables
     *
     * @var array $vars
     */
    private $vars;

    /**
     * Constructor
     *
     * Template directory must be set before instantiating a View
     *
     * @throws Exception If template directory not set
     */
    public function __construct()
    {
        if (self::$templateDirectory == null) {
            throw new Exception('Template directory not set');
        }
        $this->vars = array();
    }

    /**
     * Get assigned variable with magic getter
     *
     * @param $key Name of variable to get
     * @return mixed Value of variable or null if not found
     */
    public function __get($key)
    {
        if (isset($this->vars['key'])) {
            return $this->vars['key'];
        } else {
            return null;
        }
    }

    /**
     * Assigns a variable to the view with magic setter
     * @param string $key Name of variable to assign
     * @param mixed $value Value of variable to assign
     */
    public function __set($key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     * Assign a variable to the view
     *
     * If key is an array it is assumed to be an array of variables to assign
     *
     * @param string|array $key Name of the variable or array of variables
     * @param mixed $value Value of the variable
     */
    public function assign($key, $value = null)
    {
        if (is_string($key)) {
            $this->vars[$key] = $value;
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        }
    }

    /**
     * Get view file, insert variables and then return result
     *
     * @param string $file Filename
     * @return string Contents of view
     * @throws Exception If file does not exist
     */
    public function fetch($file)
    {
        extract($this->vars);

        ob_start();
        if (file_exists(self::$templateDirectory.'/'.$file)) {
            include(self::$templateDirectory.'/'.$file);
        } else {
            throw new Exception("View file `".$file."` not found");
        }
        $view = ob_get_contents();
        ob_end_clean();

        return $view;
    }

    /**
     * Render view with layout if layout is enabled
     *
     * @param string $file Name of view file
     * @param string $layoutFile Name of layout file
     * @return string Content of view and layout file
     */
    public function render($file, $layoutFile = 'layout.phtml')
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
        return $result;
    }

    /**
     * Load and run view helper
     *
     * @param string $name Name of helper
     * @param array $args Arguments for helper
     */
    public function __call($name, $args)
    {
        $helper = View\Helper\Loader::load($name);

        return call_user_func_array(
            array($helper, 'run'),
            array($args)
        );
    }

    /**
     * Set template directory
     *
     * @param string $path Path of template directory
     */
    public static function setTemplateDirectory($path)
    {
        if (is_dir($path)) {
            self::$templateDirectory = $path;
        }
    }
}

