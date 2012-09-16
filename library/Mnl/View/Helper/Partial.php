<?php
/**
 * Renders a partial view
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View\Helper;

/**
 * Renders a view inside another view
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Partial extends \Mnl\View\HelperAbstract
{
    /**
     * Return a rendered view
     *
     * @param array $args First element is filename of partial second element is data to assign to partial
     * @return string Rendered view
     */
    public static function run($args)
    {
        $file = $args[0];
        if (isset($args[1])) {
            $data = $args[1];
        } else {
            $data = array();
        }
        $partial = new \Mnl\View();
        $partial->assign($data);

        return $partial->fetch($file);
    }
}
