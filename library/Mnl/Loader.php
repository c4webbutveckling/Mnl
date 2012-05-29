<?php
/**
 * Mnl_Loader
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl;
class Loader
{
    protected $_paths;

    public function __construct()
    {
        $this->_paths = array();
    }

    public function autoload($class)
    {
        $namePath = str_replace('_', '/', $class);
        $namePath = str_replace('\\', '/', $namePath);

        if (file_exists(dirname(realpath(__FILE__)).$namePath.'.php')) {
            include dirname(realpath(__FILE__)).$namePath.'.php';
        }
        foreach ($this->_paths as $path) {
            if (file_exists($path.$namePath.'.php')) {
                include $path.$namePath.'.php';
            }
        }
    }

    public function registerPath($path)
    {
        if (is_dir($path) && !in_array($path, $this->_paths)) {
            $this->_paths[] = $path;

            return true;
        }

        return false;
    }

    public function registerAutoload()
    {
        spl_autoload_register(array($this, 'autoload'));
    }
}
