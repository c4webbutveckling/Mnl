<?php
/**
 * Mnl_View_Helper_Abstract
 *
 * @category Mnl
 * @package  Mnl_View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
abstract class Mnl_View_Helper_Abstract implements Mnl_View_Helper_Interface
{
    public static function run($args)
    {
        return $args;
    }
}
