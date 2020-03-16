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
class Zend_Db_Adapter_Odbc_Db2 extends Zend_Db_Adapter_Odbc_Abstract
{

    /**
     * Odbc type.
     *
     * @var string
     */
    protected $_odbcType = 'Db2';
    
    private $_isI5 = true;

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
        'INTEGER'            => Zend_Db::INT_TYPE,
        'SMALLINT'           => Zend_Db::INT_TYPE,
        'BIGINT'             => Zend_Db::BIGINT_TYPE,
        'DECIMAL'            => Zend_Db::FLOAT_TYPE,
        'NUMERIC'            => Zend_Db::FLOAT_TYPE
    );
	
    /**
     * Default class name for a DB statement.
     *
     * @var string
     */
    protected $_defaultStmtClass = 'Zend_Db_Statement_Odbc_Db2';	

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
        // Ensure the connection is made so that _isI5 is set
        $this->_connect();

        if ($schemaName === null && $this->_config['schema'] != null) {
            $schemaName = $this->_config['schema'];
        }

        if (!$this->_isI5) {

            $sql = "SELECT DISTINCT c.tabschema, c.tabname, c.colname, c.colno,
                c.typename, c.default, c.nulls, c.length, c.scale,
                c.identity, tc.type AS tabconsttype, k.colseq
                FROM syscat.columns c
                LEFT JOIN (syscat.keycoluse k JOIN syscat.tabconst tc
                ON (k.tabschema = tc.tabschema
                    AND k.tabname = tc.tabname
                    AND tc.type = 'P'))
                ON (c.tabschema = k.tabschema
                    AND c.tabname = k.tabname
                    AND c.colname = k.colname)
                WHERE "
                . $this->quoteInto('UPPER(c.tabname) = UPPER(?)', $tableName);

            if ($schemaName) {
               $sql .= $this->quoteInto(' AND UPPER(c.tabschema) = UPPER(?)', $schemaName);
            }

            $sql .= " ORDER BY c.colno";

        } else {

            // DB2 On I5 specific query
            $sql = "SELECT DISTINCT C.TABLE_SCHEMA, C.TABLE_NAME, C.COLUMN_NAME, C.ORDINAL_POSITION,
                C.DATA_TYPE, C.COLUMN_DEFAULT, C.NULLS ,C.LENGTH, C.SCALE, LEFT(C.IDENTITY,1),
                LEFT(tc.TYPE, 1) AS tabconsttype, k.COLSEQ
                FROM QSYS2.SYSCOLUMNS C
                LEFT JOIN (QSYS2.syskeycst k JOIN QSYS2.SYSCST tc
                    ON (k.TABLE_SCHEMA = tc.TABLE_SCHEMA
                      AND k.TABLE_NAME = tc.TABLE_NAME
                      AND LEFT(tc.type,1) = 'P'))
                    ON (C.TABLE_SCHEMA = k.TABLE_SCHEMA
                       AND C.TABLE_NAME = k.TABLE_NAME
                       AND C.COLUMN_NAME = k.COLUMN_NAME)
                WHERE "
                . $this->quoteInto('UPPER(C.TABLE_NAME) = UPPER(?)', $tableName);

            if ($schemaName) {
                $sql .= $this->quoteInto(' AND UPPER(C.TABLE_SCHEMA) = UPPER(?)', $schemaName);
            }

            $sql .= " ORDER BY C.ORDINAL_POSITION FOR FETCH ONLY";
        }

        $desc = array();
        $stmt = $this->query($sql);

        /**
         * To avoid case issues, fetch using FETCH_NUM
         */
        $result = $stmt->fetchAll(Zend_Db::FETCH_NUM);

        /**
         * The ordering of columns is defined by the query so we can map
         * to variables to improve readability
         */
        $tabschema      = 0;
        $tabname        = 1;
        $colname        = 2;
        $colno          = 3;
        $typename       = 4;
        $default        = 5;
        $nulls          = 6;
        $length         = 7;
        $scale          = 8;
        $identityCol    = 9;
        $tabconstType   = 10;
        $colseq         = 11;

        foreach ($result as $key => $row) {
            list ($primary, $primaryPosition, $identity) = array(false, null, false);
            if ($row[$tabconstType] == 'P') {
                $primary = true;
                $primaryPosition = $row[$colseq];
            }
            /**
             * In IBM DB2, an column can be IDENTITY
             * even if it is not part of the PRIMARY KEY.
             */
            if ($row[$identityCol] == 'Y') {
                $identity = true;
            }

            // only colname needs to be case adjusted
            $desc[$this->foldCase($row[$colname])] = array(
                'SCHEMA_NAME'      => $this->foldCase($row[$tabschema]),
                'TABLE_NAME'       => $this->foldCase($row[$tabname]),
                'COLUMN_NAME'      => $this->foldCase($row[$colname]),
                'COLUMN_POSITION'  => (!$this->_isI5) ? $row[$colno]+1 : $row[$colno],
                'DATA_TYPE'        => $row[$typename],
                'DEFAULT'          => $row[$default],
                'NULLABLE'         => (bool) ($row[$nulls] == 'Y'),
                'LENGTH'           => $row[$length],
                'SCALE'            => $row[$scale],
                'PRECISION'        => ($row[$typename] == 'DECIMAL' ? $row[$length] : 0),
                'UNSIGNED'         => false,
                'PRIMARY'          => $primary,
                'PRIMARY_POSITION' => $primaryPosition,
                'IDENTITY'         => $identity
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
