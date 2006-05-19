<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

// Content of File: 
// 1. class Dao
// 2. class Db
// 3. class DbResult
// 4. class DbMysql extends Db
// 5. class DbMysqlResult extends DbResult


// +----------------------------------------------------------------+
// | Base class for Data Access Objects.                            |
// | Author: Cezary Tomczak [www.gosu.pl]                           |
// | Free for any use as long as all copyright messages are intact. |
// +----------------------------------------------------------------+

class Dao {
    var $Db;
    var $table;
    var $pk;
    var $seq;
       
    function Db(&$Db) {
        $this->Db =& $Db;
    }
    // Returns ISO date.
    function now() {
        switch ($this->Db->driver) {
            case 'mysql': $q = "SELECT NOW()"; break;
            case 'oci8': $q = "SELECT SYSDATE FROM dual"; break;
            default: return trigger_error('Dao::now() not supported for this db driver', E_USER_ERROR);
        }
        return $this->Db->getOne($q);
    }
    function condition($key, $val) {
        return (is_null($val) ? "$key IS NULL" : $this->Db->bind("$key = ?", $val));
    }
    function insertId() {
        switch ($this->Db->driver) {
            case 'mysql': return $this->Db->insertId();
            case 'oci8': return $this->Db->getOne("SELECT {$this->seq}.currval FROM dual");
            default: return trigger_error('Dao::insertId() not implemented for this db driver', E_USER_ERROR);
        }
    }
    function find($id) {
        return $this->Db->getRow("SELECT * FROM {$this->table} WHERE {$this->pk} = ?", $id);
    }
    function insert($fields) {
        $this->Db->insert($this->table, $fields);
    }
    function update($fields) {
        $where = $this->Db->bind($this->pk . ' = ?', $fields[$this->pk]);
        unset($fields[$this->pk]);
        $this->Db->update($this->table, $fields, $where);
    }
    function delete($id) {
        $this->Db->execute("DELETE FROM {$this->table} WHERE {$this->pk} = ?", $id);
    }
    function count() {
        return $this->Db->getOne("SELECT COUNT(*) FROM {$this->table}");
    }
}

// +-----------------------------------------------------------------+
// | Database abstraction library (supporting mysql, oracle, pgsql). |
// | Author: Cezary Tomczak [www.gosu.pl]                            |
// | Free for any use as long as all copyright messages are intact.  |
// +-----------------------------------------------------------------+

define('DB_FETCH_ASSOC', 1);
define('DB_FETCH_NUM', 2);
define('DB_FETCH_BOTH', 3);
define('DB_FETCH_OBJECT', 4);

// --
// Db
// --

class Db {
    var $prepareTokens = array();
    var $prepareQueries = array();

    var $driver;
    var $Debug;

    var $fetchmode = DB_FETCH_ASSOC;
    var $autoFreeResult = 1;
    var $autoCommit = 1;
    var $lastQuery = "";
    var $_TconnectId;
        
   function Db() {
        if (!extension_loaded($this->driver)) {
            trigger_error("Db::__construct() failed, '{$this->driver}' extension not loaded", E_USER_ERROR);
        }
        register_shutdown_function(array(&$this, 'destruct'));
    }
    function destruct() {
        if ($this->autoCommit == 0) { $this->rollback(); }
    }

    // Check whether query is a data selection query.
    function isSelect($query) {
        $s = 'SELECT|SHOW|CHECK|REPAIR|OPTIMIZE|ANALYZE|EXPLAIN|DESCRIBE';
        return preg_match('/^\s*('.$s.')\s+/i', $query);
    }
    function isConnected() {
        return is_resource($this->connectId) || is_object($this->connectId);
    }
    // $options = array('host' => ?, 'username' => ?, 'password' => ?, 'database' => ?, 'persistent' => ?);
    function connect($options) {
        $this->_connect($options);
        if (!$this->isConnected()) {
            return trigger_error(serialize(array(
                'message'  => 'Db::connect() failed',
                'error' => $this->getError()
            )), E_USER_ERROR);
        }
    }
    function disconnect() {
        $ret = $this->_disconnect();
        $this->connectId = null;
        return $ret;
    }
    // returns object|resource|true|false
    function simpleQuery($query) {
        return $this->_query($query);
    }
    // returns object|void
    function query($query) {
        if (!$this->isConnected()) { return trigger_error('Db::query() called, but not connected', E_USER_ERROR); }
        if ($this->Debug) { $this->Debug->start(); }

        $resultId = $this->_query($query);
        $this->lastQuery = $query;

        if ($resultId === false || $resultId === null) {
            return trigger_error(serialize(array(
                'message' => 'Db::query() failed',
                'error' => $this->getError(),
                'lastQuery' => $this->lastQuery
            )), E_USER_ERROR);
        }
        $result = null;
        if (is_resource($resultId) || is_object($resultId)) {
            $type = ucfirst($this->driver);
            $class = "Db{$type}Result";
            $result =& new $class($resultId, $this);
        }
        if ($this->Debug) { $this->Debug->end($query, $result); }
        if (is_object($result)) {
            return $result;
        }
    }
    function quote($s) {
        switch (true) {
        	#case is_object($s): return $s; # ??? geht das so
            case is_null($s):   return 'NULL';
            case is_int($s):    return $s;
            case is_float($s):  return $s;
            case is_bool($s):   return (int) $s;
            case is_string($s): return "'" . str_replace("'", "''", $s) . "'";
            default: return trigger_error(sprintf("Db::quote() failed, invalida data type: '%s'", gettype($s)), E_USER_ERROR);
        }
    }
    function quoteLike($s) {
        return str_replace(array("'",  '%',  '_'), array("''", '\%', '\_'), $s);
    }
    // Last insert id
    function insertId() {
        $id = $this->_insertId();
        if (is_int($id) && ($id < 1)) {
            return trigger_error(serialize(array(
                'message' => 'Db::insertId() failed',
                'error' => $this->getError(),
                'lastQuery' => $this->lastQuery
            )), E_USER_ERROR);
        }
        return $id;
    }
    // Number of affected rows, after INSERT, UPDATE or DELETE query
    function affectedRows() {
        $rows = $this->_affectedRows();
        if (is_int($rows) && ($rows < 0)) {
            return trigger_error(serialize(array(
                'message' => 'Db::affectedRows() failed',
                'error' => $this->getError(),
                'lastQuery'   => $this->lastQuery
            )), E_USER_ERROR);
        }
        return $rows;
    }

    // Binds variables passed to the function with the given query.
    // returns string
    function bind($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $tokens = explode('?', $query);
        if (($c1 = count($data)) != ($c2 = (count($tokens) - 1) )) {
            $this->lastQuery = $query;
            return trigger_error(serialize(array(
                'message' => "Db::bind() failed, sizeof data ($c1) != sizeof params ($c2)",
                'error' => $this->getError(),
                'lastQuery' => $this->lastQuery
            )), E_USER_ERROR);
        }
        $s = $tokens[0];
        for ($i = 0; $i < $c1; ++$i) {
            $s .= ( $this->quote($data[$i]) . $tokens[$i + 1] );
        }
        return $s;
    }
    // Prepare query (emulated).
    // returns resource handle for the query (statement)
    function prepare($query) {
        $this->prepareTokens[] = explode('?', $query);
        end($this->prepareTokens);
        $k = key($this->prepareTokens);
        $this->prepareQueries[$k] = $query;
        return $k;
    }
    // Execute query or statement (emulated).
    // $stmt - query or prepared query handler
    // returns object|void
    function execute($stmt, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        if (!is_int($stmt)) {
            $query = $stmt;
            $tokens = explode('?', $query);
        } else {
            $tokens = $this->prepareTokens[$stmt];
            $query  = $this->prepareQueries[$stmt];
        }
        if (!isset($tokens) || !is_array($tokens) || !count($tokens)) {
            $this->lastQuery = $query;
            return trigger_error(serialize(array(
                'message'   => 'Db::execute() failed, invalid statement',
                'error'     => $this->getError(),
                'lastQuery' => $this->lastQuery
            )), E_USER_ERROR);
        }
        if (($c1 = count($data)) != ($c2 = (count($tokens) - 1))) {
            $this->lastQuery = $query;
            return trigger_error(serialize(array(
                'message'   => "Db::execute() failed, sizeof data ($c1) != sizeof params ($c2)",
                'error'     => $this->getError(),
                'lastQuery' => $this->lastQuery
            )), E_USER_ERROR);
        }
        $realquery = $tokens[0];
        for ($i = 0; $i < $c1; ++$i) {
            $realquery .= ( $this->quote($data[$i]) . $tokens[$i + 1] );
        }
        return $this->query($realquery);
    }
    // Free statement resource that was created with prepare() (emulated)
    function free() {
        $args = func_get_args();
        if (count($args) == 0) { return trigger_error('Db::free() failed, no arguments found', E_USER_ERROR); }
        foreach ($args as $stmt) {
            if (!isset($this->prepareTokens[$stmt])) {
                return trigger_error('Db::free() failed, invalid statement resource', E_USER_ERROR);
            }
            unset($this->prepareTokens[$stmt], $this->prepareQueries[$stmt]);
        }
    }
    // Auto insert query.
    // $fields - associative array with fields names as keys and fields values as values.
    function insert($table, $fields) {
        if (!count($fields)) { return trigger_error('Db::insert() failed, array $fields is empty', E_USER_ERROR); }
        $cols = '';
        $vals = '';
        $first = true;
        foreach ($fields as $k => $v) {
            if ($first) {
                $cols .= $k;
                $vals .= $this->quote($v);
                $first = false;
            } else {
                $cols .= ',' . $k;
                $vals .= ',' . $this->quote($v);
            }
        }
        $query = "INSERT INTO $table ($cols) VALUES ($vals)";
        return $this->query($query);
    }
    // Auto update query.
    // $fields - associative array with fields names as keys and fields values as values
    // $where - string to put after the WHERE statement, you have to quote values there with quote() method
    function update($table, $fields, $where) {
        if (!count($fields)) { return trigger_error('Db::update() failed, array $fields is empty', E_USER_ERROR); }
        $set = '';
        $first = true;
        foreach ($fields as $k => $v) {
            if ($first) {
                $set   .= $k . '=' . $this->quote($v);
                $first  = false;
            } else {
                $set .= ',' . $k . '=' . $this->quote($v);
            }
        }
        $query = "UPDATE $table SET $set WHERE $where";
        return $this->query($query);
    }
    function getOne($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $Rs = (count($data) ? $this->execute($query, $data) : $this->query($query));
        return $Rs->fetchOne();
    }
    function getRow($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $Rs = (count($data) ? $this->execute($query, $data) : $this->query($query));
        $row = $Rs->fetchRow();
        $Rs->free();
        return $row;
    }
    function getCol($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $Rs = (count($data) ? $this->execute($query, $data) : $this->query($query));
        return $Rs->fetchCol();
    }
    function getAssoc($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $Rs = (count($data) ? $this->execute($query, $data) : $this->query($query));
        return $Rs->fetchAssoc();
    }
    function getAll($query, $data = array()) {
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        } else if (!is_array($data)) {
            $data = array($data);
        }
        $Rs = (count($data) ? $this->execute($query, $data) : $this->query($query));
        return $Rs->fetchAll();
    }
}

// --------
// DbResult
// --------

class DbResult {
    var $Db;
    var $fetchmode;
    var $resultId;
    
    function DbResult($resultId, &$Db) {
        $this->resultId = $resultId;
        $this->fetchmode = $Db->fetchmode;
        $this->Db = $Db;
        register_shutdown_function(array(&$this, 'destruct'));
    }
    function destruct() {
        if (is_resource($this->resultId) || is_object($this->resultId)) {
            $this->free();
        }
    }

    // Fetch 1st element in 1st row (and free result resource).
    function fetchOne() {
        $row = $this->fetchRow(DB_FETCH_NUM);
        if (!is_array($row)) { return false; }
        if (array_key_exists(0, $row)) {
            if ($this->Db->autoFreeResult) { $this->free(); }
            return $row[0];
        } else {
            return null;
        }
    }
    // Fetch 1st column from all rows into one array.
    function fetchCol() {
        $rows = array();
        $row = $this->fetchRow(DB_FETCH_NUM);
        if (!is_array($row)) { return $rows; }
        if (isset($row[0])) {
            $rows[] = $row[0];
        } else {
            return trigger_error('DbResult::fetchCol() failed, no column found', E_USER_ERROR);
        }
        while ($row = $this->fetchRow(DB_FETCH_NUM)) {
            $rows[] = $row[0];
        }
        return $rows;
    }
    // Fetch rows into associative array, using 1st column as key, 2nd column as value.
    function fetchAssoc() {
        $rows = array();
        $row = $this->fetchRow(DB_FETCH_NUM);
        if (!is_array($row)) { return $rows; }
        if (isset($row[0]) && isset($row[1])) {
            $rows[$row[0]] = $row[1];
        } else {
            return trigger_error('DbResult::fetchAssoc() failed, two columns required', E_USER_ERROR);
        }
        while ($row = $this->fetchRow(DB_FETCH_NUM)) {
            $rows[$row[0]] = $row[1];
        }
        return $rows;
    }
    // Fetch all rows.
    function fetchAll($fetchmode = null) {
        $rows = array();
        while ($rows[] = $this->fetchRow($fetchmode));
        array_pop($rows);
        return $rows;
    }
}

// -------
// DbMysql
// -------

class DbMysql extends Db {
    var $driver = 'mysql';
    var $query_counter = 0;
    var $Query_filter;
     
    function quote($s) {
        switch (true) {
        	#case is_object($s): return $s;
            case is_null($s):   return 'NULL';
            case is_int($s):    return $s;
            case is_float($s):  return $s;
            case is_bool($s):   return (int) $s;
            case is_string($s): return "'" . mysql_real_escape_string($s, $this->connectId) . "'";
            default: return trigger_error(sprintf("Db::quote() failed, invalida data type: '%s'", gettype($s)), E_USER_ERROR);
        }
    }
    function quoteLike($s) {
        return str_replace(array("'",  '%',  '_'), array("\'", '\%', '\_'), $s);
    }
    function _connect($o) {
        $this->connectId = @mysql_connect($o['host'], $o['username'], $o['password']);
        if ($this->isConnected()) {
            if (!@mysql_select_db($o['database'], $this->connectId)) {
                return trigger_error(serialize(array(
                    'message'  => 'Db::connect() failed',
                    'error' => $this->getError()
                )), E_USER_ERROR);
            }
        }
    }
    function _disconnect() {
        return @mysql_close($this->connectId);
    }
    // returns object|true|false
    function _query($query) {
        $this->query_counter++;
        $query_filtered = InputFilter::safeSQL($query, $this->connectId);
        return @mysql_query($query_filtered, $this->connectId);
    }
    function _affectedRows() {
        return @mysql_affected_rows($this->connectId);
    }
    function _insertId() {
        return @mysql_insert_id($this->connectId);
    }
    // Modify limit query.
    function limitQuery($query, $offset, $limit) {
        return $query .= " LIMIT $offset, $limit";
    }
    function getError() {
        if ($this->connectId) {
            return @mysql_error($this->connectId);
        }
        return "";
    }
    // Default lock mode is 'write'.
    // Example: $Db->lock(array('tab1' => 'read', 'tab2' => 'write', 'tab3'));
    function lock($tables) {
        if (!is_array($tables) || count($tables) == 0) { return trigger_error('Db::lock() failed, $tables is not an array or is empty', E_USER_ERROR); }
        $query = 'LOCK TABLES';
        foreach ($tables as $k => $v) {
            if (is_int($k)) {
                $table = $v;
                $mode = 'write';
            } else {
                $table = $k;
                $mode = $v;
            }
            $query .= " $table $mode,";
        }
        $query = substr($query, 0, -1);
        $this->query($query);
    }
    function unlock() {
        $this->query('UNLOCK TABLES');
    }
    function begin() {
        $this->query('SET AUTOCOMMIT=0');
        $this->autoCommit = 0;
        $this->query('BEGIN');
    }
    function commit() {
        $this->query('COMMIT');
        $this->query('SET AUTOCOMMIT=1');
        $this->autoCommit = 1;
    }
    function rollback() {
        $this->query('ROLLBACK');
        $this->query('SET AUTOCOMMIT=1');
        $this->autoCommit = 1;
    }
}

// -------------
// DbMysqlResult
// -------------

class DbMysqlResult extends DbResult {
    // When using single call to fetchRow(), mostly u have to free result resource by yourself.
    // returns mixed (If there are no more rows returns false)
    function fetchRow($fetchmode = null) {
        switch (isset($fetchmode) ? $fetchmode : $this->fetchmode) {
            case DB_FETCH_ASSOC:
                $ret = @mysql_fetch_assoc($this->resultId);
                break;
            case DB_FETCH_NUM:
                $ret = @mysql_fetch_row($this->resultId);
                break;
            case DB_FETCH_BOTH:
                $ret = @mysql_fetch_array($this->resultId);
                break;
            case DB_FETCH_OBJECT:
                $ret = @mysql_fetch_object($this->resultId);
                break;
            default:
                return trigger_error('DbResult::fetchRow() failed, invalid fetchmode', E_USER_ERROR);
        }
        if ($ret === null && $this->Db->autoFreeResult) {
            $this->free();
        } else if ($ret === null && (!is_object($this->resultId) || !is_resource($this->resultId))) {
            return trigger_error('Db::fetchRow() failed, result object has been already set free', E_USER_ERROR);
        }
        return $ret;
    }
    function seek($i) {
        return @mysql_data_seek($this->resultId, $i);
    }
    function free() {
        if (is_object($this->resultId) || is_resource($this->resultId)) {
            @mysql_free_result($this->resultId);
            $this->resultId = null;
        }
    }
    function numCols() {
        return @mysql_num_fields($this->resultId);
    }
    function numRows() {
        return @mysql_num_rows($this->resultId);
    }
}

?>