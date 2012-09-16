<?php
/**
 * Mnl\View\Layout
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View;

/**
 * Layout for Mnl\View
 *
 * Layout is called from the View::render method
 *
 * In the layout file {content} is replaced whith the result of View::fetch
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Layout
{
    /**
     * Container for layout variables
     *
     * @var array $vars
     */
    protected $vars;

    /**
     * Layout enabled flag
     *
     * @var boolean $enabled
     */
    protected $enabled = true;

    /**
     * View content
     *
     * @var string $viewContent
     */
    protected $viewContent;

    /**
     * Instance
     *
     * @var self $instance
     */
    protected static $instance = null;

    /**
     * Get layout instance
     *
     * @return self
     */
    public static function getLayout()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Set view content
     *
     * @param string $content Content to insert into layout
     */
    public function setViewContent($content)
    {
        $this->viewContent = $content;
    }

    /**
     * Assign a variable to the layout
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
     * Get assigned variables
     *
     * @return array Assigned variables
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Get layoutfile and insert view content
     *
     * @param string $file Filename of layout
     * @return string Complete page with layout and view content
     */
    public function fetch($file = 'layout.phtml')
    {

        $layout = new \Mnl\View();
        $layout->assign($this->vars);
        $layoutResult = $layout->fetch($file);
        $layoutResult = str_replace(
            "{content}",
            $this->viewContent,
            $layoutResult
        );

        return $layoutResult;
    }

    /**
     * Disable layout
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Check if layout is enabled
     *
     * @return boolean True if layout is enabled false otherwise
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}

