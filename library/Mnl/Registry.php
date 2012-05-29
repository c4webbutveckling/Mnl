<?php
/**
 * Mnl_Registry
 *
 * @category Mnl
 * @package  Mnl
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl;
class Registry
{
    private static $_instance;
    private static $_variables;

    private function __construct()
    {
    }

    public function __get($varName)
    {
        if (isset(self::$_variables[$varName])) {
            return self::$_variables[$varName];
        } else {
            return false;
        }
    }

    public function __set($varName, $value)
    {
        self::$_variables[$varName] = $value;
    }

    public function set($varName, $value)
    {
        self::$_variables[$varName] = $value;
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
}
