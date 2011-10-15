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
class Mnl_View_Helper_Link extends Mnl_View_Helper_Abstract
{
    
    public static function run($args)
    {
        $uri = $args[0];
        $content = $args[1];
        
        $newString = '<a href="'.$uri.'">'.$content.'</a>';
        
        return $newString;
    }
}