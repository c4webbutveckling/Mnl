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

namespace Mnl\View\Helper;

class Loader
{
    private static $instance = null;
    private $helperPaths = array();

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function registerHelperPath($prefix, $path)
    {
        if (!file_exists($path)) {
            throw new Loader\Exception(
                'Error: Path '.$path.' does not exist.'
            );
        }
        if (!isset($this->helperPaths[$prefix])) {
            $this->helperPaths[$prefix] = $path;
        }
    }

    public function getHelperPaths()
    {
        if (count($this->helperPaths) == 0) {
            throw new Loader\Exception(
                "Error: No paths registered."
            );
        }

        return $this->helperPaths;
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

        throw new Loader\Exception(
            'Error: No helper with name '.$name.' found'
        );
    }
}
