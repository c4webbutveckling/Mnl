<?php
namespace Mnl\ActiveRecord;

use Closure;
use Illuminate\Support\Facades\DB;

class Collection
{
    protected $_tableName;
    protected $_className;
    protected $_query;
    protected $_executed;

    public function __construct($tableName, $className, $clauses)
    {
        $this->_executed = false;
        $this->_tableName = $tableName;
        $this->_className = $className;
        $this->_query = DB::table($this->_tableName);

        $this->parseWhere($clauses);
    }

    protected function parseWhere($clauses)
    {
        foreach ($clauses as $key => $value) {
            $key = explode(' ', $key);
            $op = $key[1];
            $column = $key[0];

            if ($op == 'IS') {
                $this->_query->where($column, 'IS', $value);
            } elseif ($op == 'IN') {
                $this->_query->whereIn($column, $value);
            } else {
                $this->_query->where($column, $op, $value);
            }
        }
    }

    public function raw(Closure $callback)
    {
        $callback($this->_query);

        return $this;
    }

    public function where($clauses)
    {
        $this->parseWhere($clauses);

        return $this;
    }

    public function order($by, $direction = 'asc')
    {
        $this->_query->orderBy($by, $direction);

        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->_query->limit($limit);
        $this->_query->offset($offset);

        return $this;
    }

    public function getObjects()
    {
        $reflectionClass = new \ReflectionClass($this->_className);

        return $this->execute()
            ->map(function($result) use ($reflectionClass) {
                $object = $reflectionClass->newInstance();
                $object->updateAttributes((array) $result);
                return $object;
            });
    }

    public function toArray()
    {
        return $this->getObjects()->toArray();
    }

    public function toCollection()
    {
        return $this->getObjects();
    }

    public function count()
    {
        return $this->_query->count();
    }

    public function first()
    {
        if ($this->_executed) {
            return $this->_queryResult;
        }

        $result = $this->_query->first();
        if (is_null($result)) {
            $this->_queryResult = null;
        } else {
            $reflectionClass = new \ReflectionClass($this->_className);
            $object = $reflectionClass->newInstance();
            $object->updateAttributes((array) $result);
            $this->_queryResult = $object;
        }
        $this->_executed = true;

        return $this->_queryResult;
    }

    protected function execute()
    {
        if ($this->_executed) {
            return $this->_queryResult;
        }
        $this->_queryResult = $this->_query->get();
        $this->_executed = true;
        return $this->_queryResult;
    }
}
