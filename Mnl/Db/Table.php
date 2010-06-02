<?php
/**
 * Mnl_Db_Table
 *
 * @category Mnl
 * @package  Mnl_Db
 * @author   Markus Nilsson <markus@mnilsson.se>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link     http://mnilsson.se/Mnl
 */
class Mnl_Db_Table
{
    /**
     * @var string Tablename
     */
    protected $_table;
    /**
     * @var PDO Database connection
     */
    protected $_dbAdapter = null;

    protected $_statementError = null;
    protected $_pdoError = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_dbAdapter = Mnl_Db_Connection::getConnection();
    }

    /**
     *
     * @param string $where
     */
    public function fetchAll($data, $order = array())
    {
        $cols = array();
        $vals = array();

        $where = array();
        foreach ($data as $col => $val) {
            $cols[] = $col;
            $vals[] = $val;
            $where[] = $col.' = ?';
        }

        if (count($order) != 0) {
            $orderBy = ' ORDER BY '.$order[0].' '.$order[1];
        } else {
            $orderBy = '';
        }

        $stmt = $this->_dbAdapter->prepare(
            "SELECT * FROM ".$this->_table." WHERE ".implode(' AND ',$where).$orderBy
        );

        for ($i = 1; $i <= count($cols); $i++) {
            $stmt->bindParam($i, $vals[$i-1]);
        }

        $stmt->execute();
        $this->_statementError = $stmt->errorInfo();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function find($id)
    {
        $stmt = $this->_dbAdapter->prepare(
            "SELECT * FROM ".$this->_table." WHERE id = :Id"
        );
        $stmt->bindParam('Id', $id);
        $stmt->execute();
        $this->_statementError = $stmt->errorInfo();
        return $stmt->fetch();
    }


    public function insert($data)
    {
        $cols = array();
        $vals = array();

        $questionmarks = array();

        foreach ($data as $col => $val) {
            $cols[] = $col;
            $vals[] = $val;
            $questionmarks[] = '?';
        }
        $stmt = $this->_dbAdapter->prepare(
            "INSERT INTO ".$this->_table."(`"
            .implode('`, `', $cols)."`) VALUES (".implode(', ', $questionmarks).")"
        );

        for ($i = 1; $i <= count($cols); $i++) {
            $stmt->bindParam($i, $vals[$i-1]);
        }
        $result = $stmt->execute();
        $this->_statementError = $stmt->errorInfo();
        if ($result) {
            return $this->_dbAdapter->lastInsertId();
        } else {
            return false;
        }
    }

    public function update($where, $data)
    {
        $cols = array();
        $vals = array();

        $whereCols = array();
        $whereVals = array();

        foreach ($data as $col => $val) {
            $cols[] = $col;
            $vals[] = $val;
        }

        foreach ($where as $col => $val) {
            $whereCols[] = $col;
            $whereVals[] = $val;
        }

        $query = "UPDATE ".$this->_table." SET "
            .implode(' = ?, ', $cols)." = ? WHERE ".implode(' = ? AND ', $whereCols)." = ?";

        $stmt = $this->_dbAdapter->prepare($query);

        $valCounter = 0;
        for ($i = 1; $i <= count($cols); $i++) {
            $stmt->bindParam($i, $vals[$i-1]);
            $valCounter++;
        }

        for ($i = 1; $i <= count($whereCols); $i++) {
            $stmt->bindParam($valCounter+$i, $whereVals[$i-1]);
        }

        $result = $stmt->execute();
        $this->_statementError = $stmt->errorInfo();
        return $result;
    }

    public function delete($data)
    {
        $cols = array();
        $vals = array();

        $questionmarks = array();

        $where = array();
        foreach ($data as $col => $val) {
            $cols[] = $col;
            $vals[] = $val;
            $where[] = $col.' = ?';
        }

        $stmt = $this->_dbAdapter->prepare(
            "DELETE FROM ".$this->_table." WHERE ".implode(' AND ',$where)
        );

        for ($i = 1; $i <= count($cols); $i++) {
            $stmt->bindParam($i, $vals[$i-1]);
        }
        $result = $stmt->execute();
        $this->_statementError = $stmt->errorInfo();
        return $result;
    }
    
    public function getStatementErrorInfo()
    {
        return $this->_statementError;
    }
    
    public function getPdoErrorInfo()
    {
        return $this->_pdoError;
    }
}
