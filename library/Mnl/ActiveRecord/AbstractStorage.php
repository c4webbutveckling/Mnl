<?php
namespace Mnl\ActiveRecord;
abstract class AbstractStorage
{

    protected $_tableName;
    private $_primaryKey = 'id';

    public function __construct($storageConnection)
    {
        $this->_connection = $storageConnection;
    }

    public function retreive($value, $columnName = 'id')
    {
        $this->_primaryKey = $columnName;
        $stmt = $this->_connection->prepare("SELECT * FROM " . $this->_tableName . " WHERE " . $columnName . " = :Value");
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
        $stmt = $this->_connection->prepare(
            "INSERT INTO " . $this->_tableName . "(
                " . implode(',', array_keys($data)) . ")
                VALUES (" . implode(', ', $questionMarks). ")"
            );
        $stmt->execute($data);
        return $this->_connection->lastInsertId();
    }

    public function update($data)
    {
        $kvSets = array();
        $values = array();
        foreach ($data as $key => $value) {
            if ($key == $this->_primaryKey) {
                continue;
            }
            $kvSets[] = $key.' = :'.$key;
            $values[] = $value;
        }
        $stmt = $this->_connection->prepare(
            "UPDATE " . $this->_tableName . " SET ".implode(',', $kvSets) . " WHERE " . $this->_primaryKey . " = :".$this->_primaryKey
        );
        $stmt->execute($data);

    }

    public function destroy($id)
    {
        $stmt = $this->_connection->prepare("DELETE FROM " . $this->_tableName . " WHERE " . $this->_primaryKey . " = :Id");
        $stmt->bindParam('Id', $id);
        $deleteResult = $stmt->execute();
        return $deleteResult;
    }

    protected function getAvailableFields()
    {
        $stmt = $this->_connection->prepare('SHOW COLUMNS FROM ' . $this->_tableName);
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
}
