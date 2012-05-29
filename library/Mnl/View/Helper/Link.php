<?php
/**
 * Mnl_View_Helper_Link
 *
 * @category Mnl
 * @package  Mnl_View
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */

namespace Mnl\View\Helper;

class Link extends \Mnl\View\HelperAbstract
{
    
    public static function run($args)
    {
        $uri = $args[0];
        $content = $args[1];

        $newString = '<a href="'.$uri.'">'.$content.'</a>';
        
        return $newString;
    }
}
