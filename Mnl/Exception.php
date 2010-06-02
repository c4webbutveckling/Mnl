<?php
/**
 * Mnl_Exception
 *
 * @category Mnl
 * @package  Mnl_Exception
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_Exception extends Exception
{
    public function __construct($message = '')
    {
        echo $message;
    }
}
