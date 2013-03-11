<?php
/**
 * Mnl\View\HelperAbstract
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View;

/**
 * Abstract class for view helpers
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
abstract class HelperAbstract implements HelperInterface
{
    /**
     * Runner
     *
     * @param array $args Arguments
     * @return mixed Result
     */
    public static function run($args)
    {
        return $args;
    }
}
