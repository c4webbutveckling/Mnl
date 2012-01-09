<?php
/**
 * Mnl_Crud
 *
 * @category  Mnl
 * @package   Mnl
 * @author    Markus Nilsson <markus@mnilsson.se>
 * @copyright 2010 Markus Nilsson <markus@mnilsson.se>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link      http://mnilsson.se/Mnl
 */
abstract class Mnl_Crud
{
    public static $_table;
    public static $hasOne = array();

    public function load($id = 0)
    {
        if ($id == 0) {
            $id = $this->id;
        }
        $table = new Mnl_Db_Table($this::$_table);
        $result = $table->find($id);
        $this->set($result);
    }

    public function save($data = array())
    {
        $validationResult = $this->validate($data);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $table = new Mnl_Db_Table($this::$_table);
        if (isset($this->id)) {
            $table->update(array('id' => $this->id), $data);
        } else {
            $this->id = $table->insert($data);
        }
        return true;
    }

    public function validate($data)
    {
        return true;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this::$hasOne)) {
            $class = new $key;
            $columnName = $this::$hasOne[$key];
            $class->load($this->$columnName);
            $this->$key = $class;
            return $this->$key;
        } else if (isset($this->$key)) {
            return $this->$key;
        } else {
            return null;
        }
    }

    public function set($key, $value = "")
    {
        if ($key == "") {
            return;
        }
        if(is_array($key)) {
            foreach ($key as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $this->$key = $value;
        }
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function delete()
    {
        $table = new Mnl_Db_Table($this::$_table);
        $table->delete(array('id' => $this->id));
    }

    public static function all($where = array('1' => '1'), $order = array(), $limit = 0, $offset = 0)
    {
        $class = get_called_class();
        $table = new Mnl_Db_Table($class::$_table);
        $result = $table->fetchAll($where, $order, $limit, $offset);
        $collection = array();
        foreach($result as $result) {
            $obj = new $class;
            $obj->load($result['id']);
            $collection[] = $obj;
        }
        return $collection;
    }

    public static function count($where = array('1' => '1'))
    {
        $class = get_called_class();
        $table = new Mnl_Db_Table($class::$_table);
        $result = count($table->fetchAll($where));
        return $result;
    }
}
