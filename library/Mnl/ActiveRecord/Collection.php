<?php
namespace Mnl\ActiveRecord;
class Collection
{
    protected $_connection;
    protected $_tableName;
    protected $_className;
    protected $_clauses;
    protected $_order;
    protected $_limit;

    protected $_executed;

    public function __construct($connection, $tableName, $className, $clauses)
    {
        $this->_executed = false;
        $this->_clauses = array();
        $this->_order = array();
        $this->_limit = array();
        $this->_connection = $connection;
        $this->_tableName = $tableName;
        $this->_className = $className;

        $this->parseWhere($clauses);
    }

    protected function parseWhere($clauses)
    {
        foreach ($clauses as $key => $value) {
            $key = explode(' ', $key);
            $op = $key[1];
            $key = $key[0];
            $this->_clauses[] = array(
                'column' => $key,
                'op' => $op,
                'value' => $value
            );
        }
    }

    public function where($clauses)
    {
        $this->parseWhere($clauses);

        return $this;
    }

    public function order($by, $direction = '')
    {
        $order = '`' . $by . '`';
        if ($direction != '') {
            $order .= ' '.$direction;
        }
        $this->_order[] = $order;

        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->_limit = array($limit, $offset);

        return $this;
    }

    public function getObjects()
    {
        $reflectionClass = new \ReflectionClass($this->_className);
        $results = $this->execute();
        $objects = array();
        foreach ($results as $result) {
            $object = $reflectionClass->newInstance();
            $object->updateAttributes($result);
            $objects[] = $object;
        }

        return $objects;
    }

    public function toArray()
    {
        return $this->getObjects();
    }

    public function first()
    {
        $objects = $this->getObjects();
        if (count($objects) != 0) {
            return array_pop($objects);
        } else {
            return null;
        }
    }

    protected function execute()
    {
        if ($this->_executed) {
            return $this->_queryResult;
        }
        $query = $this->buildQuery();
        $stmt = $this->_connection->prepare($query['sql']);
        $stmt->execute($query['params']);
        $this->_executed = true;

        $this->_queryResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->_queryResult;
    }

    protected function buildQuery()
    {
        $sql = "SELECT * FROM " . $this->_tableName;
        $params = array();
        if (!empty($this->_clauses)) {
            $sql .= " WHERE ";
            $clauses = array();
            foreach ($this->_clauses as $clause) {

                if($clause['op'] == 'IS') {
                    $clauses[] = '`'.$clause['column'] . "` " . $clause['op']." ".$clause['value'];
                } else {
                    $clauses[] = '`'.$clause['column'] . "` " . $clause['op'] . " :" . $clause['column'];
                    $params[$clause['column']] = $clause['value'];
                }
            }
            $sql .= implode(' AND ', $clauses);
        }

        if (!empty($this->_order)) {
            $sql .= ' ORDER BY ';
            $sql .= implode(',', $this->_order);
        }
        if (!empty($this->_limit)) {
            $sql .= ' LIMIT ';
            $sql .= implode(',', array_reverse($this->_limit));
        }

        return array(
            'sql' => $sql,
            'params' => $params
        );
    }
}
