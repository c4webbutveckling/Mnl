<?php
/**
* Mnl_View_Helper_Partial
*
* @category Mnl
* @package  Mnl_View
* @author   Markus Nilsson <markus@mnilsson.se>
* @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
* @link     http://mnilsson.se/Mnl
*/
namespace Mnl\View\Helper;
class Partial extends \Mnl\View\HelperAbstract
{
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
