<?php
/**
* PDO - Database Handler - Class
*
* PHP versions 5.1.4
*
* LICENSE:
*
*    This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Jens-André Koch <vain@clansuite.com>
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @copyright  Copyright &copy; 2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/**
* PDO is not an database-abstraction layer, it doesn't rewrite SQL or emulate missing features!
*
* It's an data-access abstraction layer, regardless which database you're using,
* you use the same functions to issue queries and fetch data.
*
* PDO gives data-access to the following Databases:
*
* DBLIB			FreeTDS / Microsoft SQL Server / Sybase
* FIREBIRD		Firebird/Interbase 6
* INFORMIX		IBM Informix Dynamic Server
* MYSQL			MySQL 3.x/4.x
* OCI			Oracle Call Interface
* ODBC			ODBC v3 (IBM DB2, unixODBC and win32 ODBC)
* PGSQL			PostgreSQL
* SQLITE		SQLite 3 and SQLite 2
*
* {@link http://wiki.cc/php/PDO_Basics }
*
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc PDO Wrapper Class
*/
class db
{
    /**
    * @desc DB Object
    */

    protected $db;

    /**
    * @desc Number of performed statements
    */

    public $query_counter = 0;

    /**
    * @desc Queries Array
    */

    public $queries = array();

    /**
    * @desc Exec's Array
    */

    public $execs = array();

    /**
    * @desc Prepare Statements Array
    */

    public $prepares = array();

    /**
    * @desc Active Queries to prevent buffering failures
    */

    public $query_active = 0;

    /**
    * @desc The active PDOStatement as reference
    */

    public $query_active_reference;

    /**
    * @desc Last SQL
    */

    public $last_sql;

    //----------------------------------------------------------------
    // Constructor
    // Create DB Object
    // Set counters to zero
    //----------------------------------------------------------------
    public function __construct($dsn, $user=NULL, $pass=NULL, $driver_options=NULL)
    {
        global $lang, $tpl, $error, $cfg;

        try
        {
            $this->db = new PDO($dsn, $user, $pass, $driver_options);

            // Error
            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            // Table-names in lower-case
            $this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
            // Fetch Mode
            /**
            * @desc UNQUOTE ON PHP 5.2 !!!
            */

            //$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
            if ( $cfg->db_type == 'mysql' )
            {
                // Buffering
                $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

                // Unicode (mysql)
                $this->exec('SET CHARACTER SET utf8');
            }
        }
        catch (PDOException $e)
        {
            $error->show( $lang->t('DB Connection Failure'), $lang->t('The Database Connection could not be established.') . '<br/> Error : ' . $e->getMessage() . '<br/>');
            die();
        }
    }

    /**
    * @desc Call forward to DB
    */

    public function __call( $func, $args )
    {
        return call_user_func_array(array($this->db, $func), $args);
    }

    /**
    * @desc Prepare a statement
    */

    public function prepare( $sql='' )
    {
		global $error, $lang;

        if( is_object($this->query_active_reference) )
        {
            $this->query_active_reference->closeCursor();
            $this->query_active_reference = NULL;
        }

        $this->last_sql = $sql;
        $this->prepares[] = $sql;

		$res = $this->db->prepare( $sql );

		if( $res )
		{
			return new db_statements( $res );
		}
		else
		{
			$error->show( $lang->t('DB Prepare Error'), $lang->t('Could not prepare the following statement:') . '<br/>' . $sql, 1);
            die();
		}

    }

    /**
    * @desc Simple Query with closeCursor() !
    */

    public function simple_query($sql='', $args = array() )
    {

        $this->last_sql = $sql;
        $res = $this->prepare( $sql );
        $res->execute( $args );
        $res->closeCursor();
        $res = NULL;

        return $res;
    }

    /**
    * @desc Deliver query to DB
    * @desc Increase counter
    */

    public function query( $sql='' )
    {
        $this->last_sql = $sql;

        if( is_object($this->query_active_reference) )
        {
            $this->query_active_reference->closeCursor();
            $this->query_active_reference = NULL;
        }

        $this->query_counter++;
        $this->queries[] = $sql;
        $res = $this->db->query($sql);

        if ( $res )
        {
            $db->query_active_reference = $res;
            return $res;
        }
        return $res;
    }

    /**
    * @desc Performs a Select returning the total number of rows found by a SELECT query
    *
    * At first it tries to get the total number of rows by
    * a mysql specific command "SQL_CALC_FOUND_ROWS".
    * If that fails an additional "found_rows" query is executed.
    *
    * Usage: $db->select
    *
    * Example Call:
    * (notice the chopped SQL, so don't use a "SELECT" in your sql-query)
    *
    *   $count = -1; #reset counter
    *   $stmt = $db->select( '* FROM foo WHERE bar < 40 LIMIT 4 OFFSET 2', $c, 4);
    *   var_dump($stmt->fetchAll()); # Normal dump of found data
    *   var_dump($count); # The number of rows found, at most 4.
    *
    * Function is fetched from the PHP Database Mailing List
    * Original Author: Rob C [ Mi, 09 November 2005 19:18 ]
    */

    public function select( $sql='', &$count = NULL, $limit = NULL)
    {
        global $error, $lang;

        $sql = 'SELECT '.(!is_null($count)?'SQL_CALC_FOUND_ROWS ':'').$sql;

        $this->last_sql = $sql;

        try {
            $res = $this->db->prepare($sql);
            $res->execute(); // Problem ! -> How to assign Vars for the ? in the sql..

            if (!is_null($count)) {
                $rows = $this->db->prepare('SELECT found_rows() AS rows');
                $rows->execute();
                $rows_array = $rows->fetch(PDO::FETCH_NUM);
                $rows->closeCursor();
                $count = $rows_array[0];

                if (!is_null($limit) && $count > $limit) {
                $count = $limit;
                }
            }
        } catch (PDOException $e) {

            $error->show( $lang->t('DB SELECT with COUNT OF ROWS Error'), $lang->t('Could not select and count the rows of following statement:') . '<br/>' . $sql, 1);
            die();
        }

        return $res;
        }


    /**
    * @desc Exec
    * @desc Increase counter
    */

    public function exec( $sql='' )
    {

        $this->last_sql = $sql;

        if( is_object($this->query_active_reference) )
        {
            $this->query_active_reference->closeCursor();
            $this->query_active_reference = NULL;
        }

        $this->exec_counter++;
        $res = $this->db->exec($sql );

        $this->execs[] = $sql;

        return $res;
    }

}

/**
* @desc Db Statements Wrapper
*/
class db_statements
{
    public $db_statement;

    /**
    * @desc Constructor
    */

    function __construct($db_pre_s)
    {
        $this->db_statement = $db_pre_s;
    }

    /**
    * @desc Non-existing methods
    */

    function __call($func, $args)
    {
        return call_user_func_array(array($this->db_statement, $func), $args);
    }

    /**
    * @desc $stmt->execute
    */
    function execute( $args = array() )
    {
        global $db;

        $db->query_counter++;
        if ( is_object( $db->query_active_reference ) )
        {
            $db->query_active_reference->closeCursor();
            $db->query_active_reference = NULL;
        }

        $db->queries[] = $this->db_statement->queryString;

        if( count($args) > 0 )
        {
            $res = $this->db_statement->execute($args);
        }
        else
        {
            $res = $this->db_statement->execute();
        }

        if ( $res )
        {
            $db->query_active_reference = $this;
            return $res;
        }
        return $res;
    }
}
?>