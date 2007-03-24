<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         errorhandling.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for PDO Database Handler
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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}

/**
 * This Clansuite Core Class for PDO Database Handler
 * 
 * PDO is not an database-abstraction layer, it doesn't rewrite SQL or emulate missing features!
 *
 * It's an data-access abstraction layer, regardless which database you're using,
 * you use the same functions to issue queries and fetch data.
 * This means that you have to watch out and take care, which functions are 
 * avaiable on all db-systems or only on some.
 *
 * PDO gives data-access to the following Databases:
 *
 * DBLIB	    FreeTDS / Microsoft SQL Server / Sybase
 * FIREBIRD		Firebird/Interbase 6
 * INFORMIX		IBM Informix Dynamic Server
 * MYSQL		MySQL 3.x/4.x
 * OCI			Oracle Call Interface
 * ODBC			ODBC v3 (IBM DB2, unixODBC and win32 ODBC)
 * PGSQL		PostgreSQL
 * SQLITE		SQLite 3 and SQLite 2
 *
 * @link http://wiki.cc/php/PDO_Basics
 *
 * Clansuite Core Class - Db 
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  db
 */
class db
{
    /**
     * @var $db
     * @access protected
     */

    protected $db;

    /**
     * Number of performed statements
     * @var integer
     */

    public $query_counter = 0;

    /**
     * Queries Array
     * @var array
     */

    public $queries = array();

    /**
     * Exec's Array
     * @var array
     /

    public $execs = array();

    /**
     * Prepare Statements Array
     * @var array
     */
     
    public $prepares = array();

    /**
     * Active Queries to prevent buffering failures
     * @var integer
     */
     
    public $query_active = 0;

    /**
     * The active PDOStatement as reference
     * @var object
     * @todo is variable type right?
     */
     
    public $query_active_reference;

    /**
    * Last SQL
    * @var string?
    * @todo is variable type right?
    */

    public $last_sql;

    /**
     * CONSTRUCTOR
     *
     * Create the DB Object
     * Set db related counters to zero
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @global $lang
     * @global $tpl
     * @global $error
     * @global $cfg
     * @todo correct var types
     */
   
   public function __construct($dsn, $user=NULL, $pass=NULL, $driver_options=NULL)
    {
        global $lang, $tpl, $error, $cfg;

        /**
         * try, try, try to set up PDO :)
         */
        try
        {
            /**
             * Create PDO object @ $db
             */
            
            $this->db = new PDO($dsn, $user, $pass, $driver_options);

            /**
             * Set the Error Attribute
             */
             
            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            /**
             * Table-names in lower-case
             */
            
            $this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
            
            /**
             * Fetch Mode
             * UNQUOTE ON PHP 5.2 !!!
             * @todo note by vain: what's this, why unquote?? note it here!! else no one will know.
             */

            /**
             * $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
             */
             
            if ( $cfg->db_type == 'mysql' )
            {
                /**
                 * Buffering
                 */
                 
                $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

                /**
                 * Unicode (mysql)
                 */
                 
                $this->exec('SET CHARACTER SET utf8');
            }
        }
        
        /**
         * In case Database Error occurs catch exception and show Error
         */        
        catch (PDOException $e)
        {
            $error->show( $lang->t('DB Connection Failure'), $lang->t('The Database Connection could not be established.') . '<br/> Error : ' . $e->getMessage() . '<br/>');
            die();
        }
    }

    /**
     * CALL
     * It's a forward to $db
     *
     * @param $func
     * @param $args
     */

    public function __call( $func, $args )
    {
        return call_user_func_array(array($this->db, $func), $args);
    }

    /**
     * Prepare a statement
     *
     * @param string
     * @global $error
     * @global $lang
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
     * This method is a Simple Query with closeCursor() !
     *
     * - It sets the last_sql to the current sql query string.
     * - prepares the query
     * - executes
     * - and closescursor finally
     * - return result
     *
     * @param string
     * @param array
     * @return $res
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
     * Deliver query to DB
     * Increase counter
     *
     * @param string    
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
     *  Performs a Select returning the total number of rows found by a SELECT query
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
     * @author Rob C [ Mi, 09 November 2005 19:18 ]
     *
     * @param string
     * @param integer
     * @param integer
     * @global $error
     * @global $lang
     * @return $res
     * @todo note by vain: is this still needed or deprecated? 
     *       i think the limit was placed here because of the need for limiting the pagination
     *       but as far as i know it's handled there
     */

    public function select( $sql='', &$count = NULL, $limit = NULL)
    {
        global $error, $lang;

        $sql = 'SELECT '.(!is_null($count)?'SQL_CALC_FOUND_ROWS ':'').$sql;

        $this->last_sql = $sql;

         /**
         * try, try, try to prepare the PDO Query :)
         */
        try {
            $res = $this->db->prepare($sql);
            $res->execute(); 
            
            /**
             * Problem ! -> How to assign Vars for the ? in the sql..
             * @todo note by vain: deprecated??
             */

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
        } 
        
        /**
         * In case Database Error occurs catch exception and show Error
         */ 
         
        catch (PDOException $e) {

            $error->show( $lang->t('DB SELECT with COUNT OF ROWS Error'), $lang->t('Could not select and count the rows of following statement:') . '<br/>' . $sql, 1);
            die();
        }

        return $res;
        }


    /**
     * Query Exec
     * Increase counter
     *
     * @param string
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
 * Clansuite Core Class - Db Statements Wrapper
 *
 * This is a simply substitution of the original PDO Statements Class 
 * simply needed to place a db-query-counter in the execution of statements.
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  db_statements
 *
 */
class db_statements
{
    public $db_statement;

    /**
     * CONSTRUCTOR
     *
     * $param object
     */

    function __construct($db_pre_s)
    {
        $this->db_statement = $db_pre_s;
    }

    /**
     * This is the Callback function for the Non-existing methods in this class
     *
     * @param array
     * @param array
     */

    function __call($func, $args)
    {
        return call_user_func_array(array($this->db_statement, $func), $args);
    }

    /**
     * This method is used for execution of statements
     * $stmt->execute
     *
     * @param array
     * @global $db
     * @return $res
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
             /**
              * in case $args is empty execute!!
              */
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