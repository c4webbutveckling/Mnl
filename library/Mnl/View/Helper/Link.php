<?php
/**
 * Link helper
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
namespace Mnl\View\Helper;

/**
 * Link helper
 *
 * Outputs a html link
 *
 * @category Mnl
 * @package  Mnl\View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Link extends \Mnl\View\HelperAbstract
{

    /**
     * Return a link
     *
     * @param string[] $args First element is url second is content of link
     * @return string Complete html link
     */
    public static function run($args)
    {
        $uri = $args[0];
        $content = $args[1];

        $newString = '<a href="'.$uri.'">'.$content.'</a>';

        return $newString;
    }
}
