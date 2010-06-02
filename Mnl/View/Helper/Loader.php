<?php
/**
 * Mnl_View_Helper_Loader
 *
 * @category Mnl
 * @package  Mnl_View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_View_Helper_Loader
{
    private static $_instance = null;
    private $_helperPaths = array();

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function registerHelperPath($prefix, $path)
    {
        if (!file_exists($path)) {
            throw new Mnl_View_Helper_Loader_Exception(
                'Error: Path '.$path.' does not exist.'
            );
        }
        if (!isset($this->_helperPaths[$prefix])) {
            $this->_helperPaths[$prefix] = $path;
        }
    }

    public function getHelperPaths()
    {
        if (count($this->_helperPaths) == 0) {
            throw new Mnl_View_Helper_Loader_Exception(
                "Error: No paths registered."
            );
        }
        return $this->_helperPaths;
    }

    public static function load($name)
    {
        $name = ucwords($name);
        $instance = self::getInstance();
        $paths = $instance->getHelperPaths();
        foreach ($paths as $prefix => $path) {
            if (file_exists($path.$name.'.php')) {
                require_once $path.$name.'.php';
                return $prefix.$name;
            }
        }

        throw new Mnl_View_Helper_Loader_Exception(
            'Error: No helper with name '.$name.' found'
        );
    }
}
