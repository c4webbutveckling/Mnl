<?php
/**
 * Helper loader
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View\Helper;

/**
 * Class for loading helpers and managing different helper paths
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Loader
{
    /**
     * Holds the instance
     *
     * @var Loader $instance
     */
    private static $instance = null;

    /**
     * Array of paths to helpers
     * @var string[] $helperPaths
     */
    private $helperPaths = array();

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Get singleton object
     *
     * @return Loader
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register a path for a helper namespace
     *
     * @param string $prefix Namespace of helpers
     * @param string $path Path to helper files
     */
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

    /**
     * Get all registered helper paths
     *
     * @return array
     */
    public function getHelperPaths()
    {
        if (count($this->helperPaths) == 0) {
            throw new Loader\Exception(
                "Error: No paths registered."
            );
        }

        return $this->helperPaths;
    }

    /**
     * Load a helper file
     *
     * @param string $name Name of the helper
     * @return string|null Name of helper class
     * @throws Loader\Exception If helper not found
     */
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
