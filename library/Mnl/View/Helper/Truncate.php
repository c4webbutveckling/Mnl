<?php
/**
 * Mnl_View_Helper_Truncate
 *
 * @category Mnl
 * @package  Mnl_View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */

namespace Mnl\View\Helper;

use Mnl\View\HelperAbstract;

class Truncate extends HelperAbstract
{

    public static function run($args)
    {
        $string = $args[0];
        $length = $args[1];
        if (isset($args[2])) {
            $padding = $args[2];
        } else {
            $padding = '';
        }

        $newString = substr($string, 0, $length);
        $newString .= $padding;

        return $newString;
    }
}
