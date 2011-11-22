<?php
/**
 * Mnl_View_Helper_Interface
 *
 * @category Mnl
 * @package  Mnl_View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View;
interface HelperInterface
{
    public static function run($args);
}

