<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
    * http://www.clansuite.com/
    *
    * File:         db.class.php
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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for Database Handling via PDO
 *
 * PDO is not an database-abstraction layer, it doesn't rewrite SQL or emulate missing features!
 *
 * It's an data-access abstraction layer, regardless which database you're using,
 * you use the same functions to issue queries and fetch data.
 * This means that you have to watch out and take care, which functions are
 * available on all db-systems or only on some.
 *
 * PDO gives data-access to the following Databases:
 *
 * DBLIB        FreeTDS / Microsoft SQL Server / Sybase
 * FIREBIRD     Firebird/Interbase 6
 * INFORMIX     IBM Informix Dynamic Server
 * MYSQL        MySQL 3.x/4.x
 * OCI          Oracle Call Interface
 * ODBC         ODBC v3 (IBM DB2, unixODBC and win32 ODBC)
 * PGSQL        PostgreSQL
 * SQLITE       SQLite 3 and SQLite 2
 *
 * We use PDO to access MySQL specific functions! 
 * If you'd like to see other Db's supported, then implement and provide functionality.
 *
 * @link http://wiki.cc/php/PDO_Basics
 *
 * Clansuite Core Class - Db
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  db
 */
class db //extends PDO
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

    public $query_counter   = 0;
    public $exec_counter    = 0;
    public $stmt_counter    = 0;

    /**
     * Queries Array
     * @var array
     */

    public $queries = array();

    /**
     * Exec's Array
     * @var array
     */

    public $execs = array();

    /**
     * Prepare Statements Array
     * @var array
     */

    public $prepares = array();

    
    /**
    * Last SQL
    * @var string?
    * @todo is variable type right?
    */

    public $last_sql;

    private $config     = null;
    private $error      = null;

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
     * @global $tpl
     * @global $error
     * @global $config
     * @todo correct var types
     */

   public function __construct(configuration $config, errorhandler $error)
   {
        $this->config = $config;
        $this->error  = $error;
        
        /**
         * try, try, try to set up PDO :)
         */
        try
        {
            # Create PDO object
            $this->db = new PDO("$config->db_type:dbname=$config->db_name;host=$config->db_host", $config->db_username, $config->db_password, array ());

            /**
             *  Set attributes on the database handle
             */

            # Set the Error Attribute
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            /**
             * Keep Database Connection persistent
             * BUG: Error in my_thread_global_end()
             * @link: http://bugs.php.net/bug.php?id=41350
             * @todo note by vain: if problem is fixed, reenable for performance reasons
             */            
            #$this->db->setAttribute(PDO::ATTR_PERSISTENT, true);
            
            # Force table-column names to lower case
            $this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);

            /**
             * Workaround for PDO Bug by Wez Furlong (netevil.org)
             * BUG: SQL-String containing the chars : or ? throws pdo error,
             * when creating prepared Statement, because waiting for assign-parameters
             */
            if(defined('PDO::ATTR_EMULATE_PREPARES'))
            {
                # these pdo settings require atleast PHP >= 5.1.3
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            }
            else
            {
                # fallback for earlier versions
                $this->db->setAttribute(PDO::MYSQL_ATTR_DIRECT_QUERY, true);
            }

            /**
             * Fetch Mode
             */
             if ( version_compare( phpversion(), '5.2', '<' ) )
             {
                $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
             }

            if ( $config['db_type'] == 'mysql' )
            {
                # Use buffered queries
                $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

                # SET CHARACTER Collation to UTF-8 / Unicode (mysql)
                # @link http://dev.mysql.com/doc/refman/5.0/en/charset-syntax.html
                # @todo change to config value?
                $this->exec('SET CHARACTER SET utf8');
            }
        }
        # In case a PDOException occurs, catch the exception and show Error
        catch (PDOException $exception)
        {
            $this->error->ysod( $exception, _('DB Connection Failure'), _('The Database Connection could not be established.'), 3);
            exit();
        }
    } 
    /**
     * CALL magic-method -> function-forward to $db
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
    
    public function prepare($sql) 
    {
        $this->stmt_counter++;
        
        $this->last_sql = $sql;
        $this->prepares[] = $sql;
       
        $pdo_statement = call_user_func_array(array($this->db, 'prepare'), $sql);
       
        if( $pdo_statement )
        {
            return new db_statements($this, $pdo_statement );
        }
        else
        {
            // @todo instead die -> try/catch or throw
            $this->error->show( $this->lang->t('DB Prepare Error'), $this->lang->t('Could not prepare the following statement:') . '<br/>' . $sql, 1);
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
        #benchmark::timemarker('db_begin', 'Database Simple_Query | Query No.'. $this->query_counter);
        $res->execute( $args );
        #benchmark::timemarker('db_end', 'Database Simple_Query | Query No.'. $this->query_counter);
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
    {   $this->exec_counter++;
        $this->stmt_counter++;
        
        $this->last_sql = $sql;
        $this->queries[] = $sql;
          
        #benchmark::timemarker('db_begin', 'Database Query | Query No.'. $this->query_counter);
        $res = $this->db->query($sql);
        #benchmark::timemarker('db_end', 'Database Query | Query No.'. $this->query_counter);
       
        return $res;
    }

    /**
     * Performs a Select returning the total number of rows found by a SELECT query
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
     *       i think the limit was placed here because of the need for limiting the pagination,
     *       but as far as i know it's handled there.
     */

    public function select( $sql='', &$count = NULL, $limit = NULL)
    {
        $lang   = $this->injector->instantiate('language');
        $error  = $this->injector->instantiate('errorhandler');

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

        // In case Database Error occurs catch exception and show Error
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
        $this->exec_counter++;
        $this->last_sql = $sql;
        $this->execs[] = $sql;

        #benchmark::timemarker('db_begin', 'Database Exec | No.'. $this->exec_counter);
        $res = $this->db->exec($sql );
        #benchmark::timemarker('db_end', 'Database Exec | No.'. $this->exec_counter);

        return $res;
    }
    
    /**
     * tabledescription
     * - mysql specific command 
     * - SHOW COLUMNS displays information about the columns in a given table
     */
    public function tabledescription($tablename)
    {
        $result = $this->query('SHOW COLUMNS FROM ' . $tablename);
        $tableinfos = array();
        foreach ($result as $row)
        {
            $tableinfos[$row['field']] = array( 'pk' => $row['key'] == 'PRI',
                                                'type' => $row['type'],);
        }
        return $tableinfos;
    }
    
    public function foundRows()
    {
        $rows = $this->db->prepare('SELECT found_rows() AS rows', array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE));
        $rows->execute();
        $rowsCount = $rows->fetch(PDO::FETCH_OBJ)->rows;
        $rows->closeCursor();
        return $rowsCount;
    }
    
    public static function getDbObject()
    {
        return $this->db;   
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
class db_statements //extends PDOStatement
{
    protected $db;
    protected $db_statement;

    /**
     * CONSTRUCTOR
     *
     * $param object
     */

    function __construct($db, $db_pre_s)
    {
        $this->db           = $db;
        $this->db_statement = $db_pre_s;
    }

    /**
     * CALL magic method -> callback function forwarding all 
     * non-existing methods in this class
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
        $this->db->stmt_counter++;
        $this->db->queries[] = $this->db_statement->queryString;
  
        #benchmark::timemarker('db_begin', 'Database Statement | No.'. $this->db->stmt_counter);
        $res = $this->db_statement->execute($args);
        #benchmark::timemarker('db_end', 'Database Statement | No.'. $this->db->stmt_counter);
     
        return $res;
    }        
}
?>