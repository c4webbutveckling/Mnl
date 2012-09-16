<?php
/**
 * Mnl\View\Helper\Truncate
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */

namespace Mnl\View\Helper;

use Mnl\View\HelperAbstract;

/**
 * View helper for truncating a string
 *
 * Examples for use in view file:
 * <code>
 * echo $this->truncate('Foo bar', 3);
 * echo $this->truncate('Foo bar', 3, '...');
 * </code>
 * Result:
 * <code>
 * Foo
 * Foo...
 * </code>
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Truncate extends HelperAbstract
{

    /**
     * Run helper
     *
     * @param array $args Array of arguments for the helper
     * @return mixed Helper result
     */
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
