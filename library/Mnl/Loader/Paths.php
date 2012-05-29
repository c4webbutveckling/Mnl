<?php
/**
 * Mnl_Loader_Paths
 *
 * @category Mnl
 * @package  Mnl_Loader
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_Loader_Paths
{
    private static $_instance = null;
    private $_paths = array();

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

    public function registerPath($prefix, $path)
    {
        if (file_exists($path) &&!isset($this->_paths[$prefix])) {
            $this->_paths[$prefix] = $path;
        }
    }

    public function setPaths($paths)
    {
        $this->_paths = $paths;
    }

    public function getPaths()
    {
        return $this->_paths;
    }
}
