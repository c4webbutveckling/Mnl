<?php

namespace Mnl\ActiveRecord;

use Illuminate\Support\Facades\DB;
use Mnl\Utilities\Inflector;

abstract class AbstractStorage
{

    protected $_id;
    protected static $tableName;

    private $_primaryKey = 'id';

    public static function find($value, $columnName = 'id')
    {
        if (!static::$tableName) {
            $reflector = new \ReflectionClass(get_called_class());
            $inflector = new Inflector();
            $tableName = $inflector->tableize($reflector->getName());
        } else {
            $tableName = static::$tableName;
        }

        $data = DB::table($tableName)
            ->where($columnName, $value)
            ->first();
        if (!is_null($data)) {
            return (array) $data;
        }

        return null;
    }

    public static function latest($columnName = 'id')
    {
        if (!static::$tableName) {
            $reflector = new \ReflectionClass(get_called_class());
            $inflector = new Inflector();
            $tableName = $inflector->tableize($reflector->getName());
        } else {
            $tableName = static::$tableName;
        }

        $data = DB::table($tableName)
            ->orderByDesc($columnName)
            ->first();
        if (!is_null($data)) {
            return (array) $data;
        }

        return null;
    }

    public function create($data)
    {
        return DB::table($this->_tableName)
            ->insertGetId([
                $data
            ]);
    }

    public function update($data)
    {
        DB::table($this->_tableName)
            ->where($this->_primaryKey, $this->_id)
            ->update([
                $data
            ]);
    }

    public function delete()
    {
        return DB::table($this->_tableName)
            ->where($this->_primaryKey, $this->_id)
            ->delete();
    }

    protected function getAvailableFields()
    {
        return DB::statement('SHOW COLUMNS FROM ' . $this->_tableName);
    }

    public function getAvailableFieldNames()
    {
        $columnData = $this->getAvailableFields();
        $names = array();
        foreach ($columnData as $column) {
            $names[] = $column->Field;
        }

        return $names;
    }

    protected function setTable($tableName)
    {
        $this->_tableName = $tableName;
    }
}
