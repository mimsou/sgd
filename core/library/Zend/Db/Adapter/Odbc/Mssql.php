<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Sqlite.php 18214 2009-09-18 05:30:19Z ralph $
 */


/**
 * @see Zend_Db_Adapter_Odbc_Abstract
 */
require_once 'Zend/Db/Adapter/Odbc/Abstract.php';


/**
 * Class for connecting to Mssql databases and performing common operations.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Adapter_Odbc_Mssql extends Zend_Db_Adapter_Odbc_Abstract
{

    /**
     * Odbc type.
     *
     * @var string
     */
    protected $_odbcType = 'mssql';

    /**
     * Keys are UPPERCASE SQL datatypes or the constants
     * Zend_Db::INT_TYPE, Zend_Db::BIGINT_TYPE, or Zend_Db::FLOAT_TYPE.
     *
     * Values are:
     * 0 = 32-bit integer
     * 1 = 64-bit integer
     * 2 = float or decimal
     *
     * @var array Associative array of datatypes to values 0, 1, or 2.
     */
    protected $_numericDataTypes = array(
        Zend_Db::INT_TYPE    => Zend_Db::INT_TYPE,
        Zend_Db::BIGINT_TYPE => Zend_Db::BIGINT_TYPE,
        Zend_Db::FLOAT_TYPE  => Zend_Db::FLOAT_TYPE,
        'INT'                => Zend_Db::INT_TYPE,
        'SMALLINT'           => Zend_Db::INT_TYPE,
        'TINYINT'            => Zend_Db::INT_TYPE,
        'BIGINT'             => Zend_Db::BIGINT_TYPE,
        'DECIMAL'            => Zend_Db::FLOAT_TYPE,
        'FLOAT'              => Zend_Db::FLOAT_TYPE,
        'MONEY'              => Zend_Db::FLOAT_TYPE,
        'NUMERIC'            => Zend_Db::FLOAT_TYPE,
        'REAL'               => Zend_Db::FLOAT_TYPE,
        'SMALLMONEY'         => Zend_Db::FLOAT_TYPE,
    );
	
    /**
     * Default class name for a DB statement.
     *
     * @var string
     */
    protected $_defaultStmtClass = 'Zend_Db_Statement_Odbc_Mssql';	

    /**
     * Last insert id from INSERT query
     *
     * @var int
     */
    protected $_lastInsertId;

    /**
     * Query used to fetch last insert id
     *
     * @var string
     */
    protected $_lastInsertSQL = 'SELECT SCOPE_IDENTITY() as Current_Identity';

    /**
     * Returns the column descriptions for a table.
     *
     * The return value is an associative array keyed by the column name,
     * as returned by the RDBMS.
     *
     * The value of each array element is an associative array
     * with the following keys:
     *
     * SCHEMA_NAME      => string; name of schema
     * TABLE_NAME       => string;
     * COLUMN_NAME      => string; column name
     * COLUMN_POSITION  => number; ordinal position of column in table
     * DATA_TYPE        => string; SQL datatype name of column
     * DEFAULT          => string; default expression of column, null if none
     * NULLABLE         => boolean; true if column can have nulls
     * LENGTH           => number; length of CHAR/VARCHAR
     * SCALE            => number; scale of NUMERIC/DECIMAL
     * PRECISION        => number; precision of NUMERIC/DECIMAL
     * UNSIGNED         => boolean; unsigned property of an integer type
     * PRIMARY          => boolean; true if column is part of the primary key
     * PRIMARY_POSITION => integer; position of column in primary key
     * IDENTITY         => integer; true if column is auto-generated with unique values
     *
     * @todo Discover integer unsigned property.
     *
     * @param string $tableName
     * @param string $schemaName OPTIONAL
     * @return array
     */
    public function describeTable($tableName, $schemaName = null)
    {
        /**
         * Discover metadata information about this table.
         */
        $sql    = "exec sp_columns @table_name = " . $this->quoteIdentifier($tableName, true);
        $stmt   = $this->query($sql);
        $result = $stmt->fetchAll(Zend_Db::FETCH_NUM);

        $owner           = 1;
        $table_name      = 2;
        $column_name     = 3;
        $type_name       = 5;
        $precision       = 6;
        $length          = 7;
        $scale           = 8;
        $nullable        = 10;
        $column_def      = 12;
        $column_position = 16;

        /**
         * Discover primary key column(s) for this table.
         */
        $tableOwner = $result[0][$owner];
        $sql        = "exec sp_pkeys @table_owner = " . $tableOwner
                    . ", @table_name = " . $this->quoteIdentifier($tableName, true);
        $stmt       = $this->query($sql);

        $primaryKeysResult = $stmt->fetchAll(Zend_Db::FETCH_NUM);
        $primaryKeyColumn  = array();

        // Per http://msdn.microsoft.com/en-us/library/ms189813.aspx,
        // results from sp_keys stored procedure are:
        // 0=TABLE_QUALIFIER 1=TABLE_OWNER 2=TABLE_NAME 3=COLUMN_NAME 4=KEY_SEQ 5=PK_NAME

        $pkey_column_name = 3;
        $pkey_key_seq     = 4;
        foreach ($primaryKeysResult as $pkeysRow) {
            $primaryKeyColumn[$pkeysRow[$pkey_column_name]] = $pkeysRow[$pkey_key_seq];
        }

        $desc = array();
        $p    = 1;
        foreach ($result as $key => $row) {
            $identity = false;
            $words    = explode(' ', $row[$type_name], 2);
            if (isset($words[0])) {
                $type = $words[0];
                if (isset($words[1])) {
                    $identity = (bool) preg_match('/identity/', $words[1]);
                }
            }

            $isPrimary = array_key_exists($row[$column_name], $primaryKeyColumn);
            if ($isPrimary) {
                $primaryPosition = $primaryKeyColumn[$row[$column_name]];
            } else {
                $primaryPosition = null;
            }

            $desc[$this->foldCase($row[$column_name])] = array(
                'SCHEMA_NAME'      => null, // @todo
                'TABLE_NAME'       => $this->foldCase($row[$table_name]),
                'COLUMN_NAME'      => $this->foldCase($row[$column_name]),
                'COLUMN_POSITION'  => (int) $row[$column_position],
                'DATA_TYPE'        => $type,
                'DEFAULT'          => $row[$column_def],
                'NULLABLE'         => (bool) $row[$nullable],
                'LENGTH'           => $row[$length],
                'SCALE'            => $row[$scale],
                'PRECISION'        => $row[$precision],
                'UNSIGNED'         => null, // @todo
                'PRIMARY'          => $isPrimary,
                'PRIMARY_POSITION' => $primaryPosition,
                'IDENTITY'         => $identity,
            );
        }

        return $desc;
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     */
    public function insert($table, array $bind)
    {
        // extract and quote col names from the array keys
        $cols = array();
        $vals = array();
        foreach ($bind as $col => $val) {
            $cols[] = $this->quoteIdentifier($col, true);
            if ($val instanceof Zend_Db_Expr) {
                $vals[] = $val->__toString();
                unset($bind[$col]);
            } else {
                $vals[] = '?';
            }
        }

        // build the statement
        $sql = "INSERT INTO "
             . $this->quoteIdentifier($table, true)
             . ' (' . implode(', ', $cols) . ') '
             . 'VALUES (' . implode(', ', $vals) . ')'
             . ' ' . $this->_lastInsertSQL;

        // execute the statement and return the number of affected rows
        $stmt   = $this->query($sql, array_values($bind));
        $result = $stmt->rowCount();

      //  $stmt->nextRowset();

        $this->_lastInsertId = $stmt->fetchColumn();

        return $result;
    }

    /**
     * Gets the last ID generated automatically by an IDENTITY/AUTOINCREMENT column.
     *
     * As a convention, on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2), this method forms the name of a sequence
     * from the arguments and returns the last id generated by that sequence.
     * On RDBMS brands that support IDENTITY/AUTOINCREMENT columns, this method
     * returns the last value generated for such a column, and the table name
     * argument is disregarded.
     *
     * @param string $tableName   OPTIONAL Name of table.
     * @param string $primaryKey  OPTIONAL Name of primary key column.
     * @return string
     */
    public function lastInsertId($tableName = null, $primaryKey = null)
    {
        if ($tableName) {
            $tableName = $this->quote($tableName);
            $sql       = 'SELECT IDENT_CURRENT (' . $tableName . ') as Current_Identity';
            return (string) $this->fetchOne($sql);
        }

        if ($this->_lastInsertId > 0) {
            return (string) $this->_lastInsertId;
        }

        $sql = $this->_lastInsertSQL;
        return (string) $this->fetchOne($sql);
    }
	
    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     *
     * @param string $sql
     * @param integer $count
     * @param integer $offset OPTIONAL
     * @return string
     * @throws Zend_Db_Adapter_Odbc_Exception
     */
     public function limit($sql, $count, $offset = 0)
     {
        $count = intval($count);
        if ($count <= 0) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = intval($offset);
        if ($offset < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }

        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
            $sort  = (stripos($orderby, ' desc') !== false) ? 'desc' : 'asc';
            $order = str_ireplace('ORDER BY', '', $orderby);
            $order = trim(preg_replace('/\bASC\b|\bDESC\b/i', '', $order));
        }

        $sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . ($count+$offset) . ' ', $sql);

        $sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ') AS inner_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ';
            $sql .= (stripos($sort, 'asc') !== false) ? 'DESC' : 'ASC';
        }
        $sql .= ') AS outer_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ' . $sort;
        }

        return $sql;
    }
	
    /**
     * Returns a list of the tables in the database.
     *
     * @return array
     */
    public function listTables()
    {
        $this->_connect();
        $sql = "SELECT name FROM sysobjects WHERE type = 'U' ORDER BY name";
        return $this->fetchCol($sql);
    }	
	
    /**
     * Check if the adapter supports real SQL parameters.
     *
     * @param string $type 'positional' or 'named'
     * @return bool
     */
    public function supportsParameters($type)
    {
        if ($type == 'positional') {
            return true;
        }

        // if its 'named' or anything else
        return false;
    }			

}
