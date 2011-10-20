<?php
namespace Mnl\ActiveRecord;

class Base extends AbstractStorage
{
    private $_id;

    private $_reflector;
    private $_storage;

    private static $_connection;

    public function __construct()
    {
        $this->_reflector = new \ReflectionObject($this);
        $inflector = new Inflector();
        $this->setTable($inflector->tableize($this->_reflector->getName()));
        if (!isset(self::$_connection)) {
            throw new Exception("Database Connection not set");
        }
        parent::__construct(self::$_connection);
    }

    public function __get($name)
    {
        if ($name == 'id') {
            return $this->_id;
        }
    }

    public function create($data = array())
    {
        if (empty($data)) {
            $data = $this->getStoreableData();
        }
        var_dump($this->_table);
        $data['created_at'] = time();
        $this->_id = parent::create($data);
        $this->applyStorageData($data);
    }

    public function retreive($value, $columnName = 'id')
    {
        $result = parent::retreive($value, $columnName);
        if ($result === false) {
            return;
        }
        $this->applyStorageData($result);
    }

    public function update($data = array())
    {
        if (empty($data)) {
            $data = $this->getStoreableData();
        }
        $data['id'] = $this->_id;
        $data['updated_at'] = time();
        parent::update($data);
        $this->applyStorageData($data);
    }

    public function destroy($id = 0)
    {
        parent::destroy($this->_id);
    }
    private function getStoreableData()
    {
        $properties = $this->_reflector->getProperties(
            \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED
        );
        $inflector = new Inflector();
        $availableFields = $this->getAvailableFieldNames();
        $data = array();
        foreach ($properties as $property) {
            $propertyName = preg_replace('/_/','',$property->getName());
            $propertyName = $inflector->underscoreize($propertyName);
            if (in_array($propertyName, $availableFields)) {
                if ($this->{$property->getName()} !== null) {
                    $data[$propertyName] = $this->{$property->getName()};
                }
            }
        }
        return $data;
    }

    private function applyStorageData($data)
    {
        $inflector = new Inflector();
        foreach ($data as $key => $value) {
            $key = $inflector->camelize($key);
            if ($key == 'id') {
                $this->_id = $value;
            } else {
                $this->$key = $value;
            }
        }
    }

    public static function where($clauses = array())
    {
        $reflector = new \ReflectionClass(get_called_class());
        $inflector = new Inflector();
        $tableName = $inflector->tableize($reflector->getName());
        $className = $reflector->getName();
        $collection = new Collection(self::$_connection, $tableName, $className, $clauses);
        return $collection;
    }

    public static function all()
    {
        return self::where();
    }


    public static function setConnection($connection)
    {
        self::$_connection = $connection;
    }
}
