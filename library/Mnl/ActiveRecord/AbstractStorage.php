<?php
namespace Mnl\ActiveRecord;
abstract class AbstractStorage
{

    protected $_tableName;
    protected static $_connection;

    private $_primaryKey = 'id';

    public function __construct()
    {
    }

    public static function find($value, $columnName = 'id')
    {
        $reflector = new \ReflectionClass(get_called_class());
        $inflector = new Inflector();
        $tableName = $inflector->tableize($reflector->getName());

        $stmt = self::$_connection->prepare("SELECT * FROM " . $tableName . " WHERE `" . $columnName . "` = :Value");
        $stmt->bindParam('Value', $value);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }

    public function create($data)
    {
        $questionMarks = array();
        foreach ($data as $column => $value) {
            $questionMarks[] = ':'.$column;
        }
        $stmt = self::$_connection->prepare(
            "INSERT INTO " . $this->_tableName . "(
                `" . implode('`, `', array_keys($data)) . "`)
                VALUES (" . implode(', ', $questionMarks). ")"
            );
        $stmt->execute($data);
        return self::$_connection->lastInsertId();
    }

    public function update($data)
    {
        $kvSets = array();
        $values = array();
        foreach ($data as $key => $value) {
            if ($key == $this->_primaryKey) {
                continue;
            }
            $kvSets[] = "`".$key.'` = :'.$key;
            $values[] = $value;
        }
        $stmt = self::$_connection->prepare(
            "UPDATE " . $this->_tableName . " SET ".implode(',', $kvSets) . " WHERE `" . $this->_primaryKey . "` = :".$this->_primaryKey
        );
        $stmt->execute($data);

    }

    public function delete($id)
    {
        $stmt = self::$_connection->prepare("DELETE FROM " . $this->_tableName . " WHERE `" . $this->_primaryKey . "` = :Id");
        $stmt->bindParam('Id', $id);
        $deleteResult = $stmt->execute();
        return $deleteResult;
    }

    protected function getAvailableFields()
    {
        $stmt = self::$_connection->prepare('SHOW COLUMNS FROM ' . $this->_tableName);
        $stmt->execute();
        $columnData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $columnData;
    }

    public function getAvailableFieldNames()
    {
        $columnData = $this->getAvailableFields();
        $names = array();
        foreach ($columnData as $column) {
            $names[] = $column['Field'];
        }
        return $names;
    }

    protected function setTable($tableName)
    {
        $this->_tableName = $tableName;
    }

    public static function setConnection($connection)
    {
        if (!is_a($connection, '\Pdo')) {
            throw new \Exception("Pdo object expected");
        }
        self::$_connection = $connection;
    }
}
