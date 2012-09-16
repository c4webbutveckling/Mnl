<?php
/**
 * Mnl\View\HelperInterface
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View;

/**
 * Interface for view helpers
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
interface HelperInterface
{
    /**
     * Runner method for helper
     *
     * @param mixed[] $args Arguments
     * @return mixed
     */
    public static function run($args);
}

