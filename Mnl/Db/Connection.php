<?php
/**
 * Mnl_Db_Connection
 *
 * @category Mnl
 * @package  Mnl_Db
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_Db_Connection
{
	private static $_db = NULL;
	
	private function __construct()
	{	
	}
	
	public static function getConnection()
	{
		return self::$_db;
	}
	
    public static function setConnection($connection = NULL)
    {
		if(!self::$_db) {
            self::$_db = $connection;
        }
    }
}
