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
class Mnl_Loader
{
    static function loadClass($class)
    {
        $nameArray = explode('_', $class);
        $namePath = implode('/', $nameArray);
        if (file_exists(APPLICATION_PATH.'../library/'.$namePath.'.php')) {
            include_once(APPLICATION_PATH.'../library/'.$namePath.'.php');
        }
        foreach (Mnl_Loader_Paths::getInstance()->getPaths() as $path) {
            if (file_exists($path.$namePath.'.php')) {
                include_once($path.$namePath.'.php');
            }
        }
    }

    public static function registerAutoload()
    {
        spl_autoload_register('Mnl_Loader::autoload');
    }

    public static function autoload($class)
    {
        self::loadClass($class);
    }
}
