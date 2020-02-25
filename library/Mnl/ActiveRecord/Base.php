<?php
namespace Mnl\ActiveRecord;

use Mnl\Utilities\Inflector;

class Base extends AbstractStorage
{
    protected $_saved;
    protected $_persisted;

    public function __construct()
    {
        $this->_saved = false;
        $this->_persisted = false;

        if (!static::$tableName) {
            $reflector = new \ReflectionClass(get_called_class());
            $inflector = new Inflector();
            $tableName = $inflector->tableize($reflector->getName());
        } else {
            $tableName = static::$tableName;
        }
        $this->setTable($tableName);
    }

    public function __get($name)
    {
        if ($name == 'id') {
            return $this->_id;
        }
    }

    public function save()
    {
        if (isset($this->_id)) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create($data = array())
    {
        if (empty($data)) {
            $data = $this->getStoreableData();
        }
        if(!isset($data['created_at'])){
            $data['created_at'] = time();
        }
        $this->_id = parent::create($data);
        $this->updateAttributes($data);
        $this->_saved = true;
    }

    public static function find($value, $columnName = 'id')
    {
        $reflector = new \ReflectionClass(get_called_class());
        $className = $reflector->getName();

        $result = parent::find($value, $columnName);
        if ($result === false) {
            return;
        }
        $object = new $className;
        $object->updateAttributes($result);

        return $object;
    }

    public static function latest($columnName = 'id')
    {
        $reflector = new \ReflectionClass(get_called_class());
        $className = $reflector->getName();

        $result = parent::latest($columnName);
        if ($result === false) {
            return;
        }
        $object = new $className;
        $object->updateAttributes($result);

        return $object;
    }

    public function update($data = array())
    {
        if (empty($data)) {
            $data = $this->getStoreableData();
        }
        $data['id'] = $this->_id;
        $data['updated_at'] = time();
        parent::update($data);
        $this->updateAttributes($data);
        $this->_saved = true;
    }

    public function delete()
    {
        parent::delete();
    }
    private function getStoreableData()
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties(
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

    public function updateAttributes($data)
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

        return $this;
    }

    public static function where($clauses = array())
    {
        $reflector = new \ReflectionClass(get_called_class());
        if (!static::$tableName) {
            $inflector = new Inflector();
            $tableName = $inflector->tableize($reflector->getName());
        } else {
            $tableName = static::$tableName;
        }
        $className = $reflector->getName();
        $collection = new Collection($tableName, $className, $clauses);

        return $collection;
    }

    public static function all()
    {
        return self::where();
    }

    public function isSaved()
    {
        return $this->_saved;
    }

    public function isPersisted()
    {
        return $this->_persisted;
    }
}
