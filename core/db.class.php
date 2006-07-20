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

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// PDO Wrapper Class
//----------------------------------------------------------------
class db
{
    //----------------------------------------------------------------
    // DB Object
    //----------------------------------------------------------------
    protected $db;
    
    //----------------------------------------------------------------
    // Number of performed statements
    //----------------------------------------------------------------
    public $query_counter = 0;
    
    //----------------------------------------------------------------
    // Queries Array
    //----------------------------------------------------------------
    public $queries = array();
    
    //----------------------------------------------------------------
    // Exec's Array
    //----------------------------------------------------------------
    public $execs = array();
    
    //----------------------------------------------------------------
    // Prepare Statements Array
    //----------------------------------------------------------------
    public $prepares = array();
    
    //----------------------------------------------------------------
    // Active Queries to prevent buffering failures
    //----------------------------------------------------------------
    public $query_active = 0;
    
    //----------------------------------------------------------------
    // The active PDOStatement as reference
    //----------------------------------------------------------------
    public $query_active_reference;
    
    //----------------------------------------------------------------
    // Constructor
    // Create DB Object
    // Set counters to zero
    //----------------------------------------------------------------
    public function __construct($dsn, $user=NULL, $pass=NULL, $driver_options=NULL)
    {   
        global $lang, $tpl;
        
        try
        {
            $this->db = new PDO($dsn, $user, $pass, $driver_options);
            
            // Error
            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            // Table-names in lower-case
            $this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
            // Fetch Mode
            //----------------------------------------------------------------
            // UNQUOTE ON PHP 5.2 !!!
            //----------------------------------------------------------------
            //$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $error->show( $lang->t('DB Connection Failure'), $lang->t('The Database Connection could not be established.') . '<br/> Error : ' . $e->getMessage() . '<br/>'); 
            die();
        }
    }
 
    //----------------------------------------------------------------
    // Call forward to DB
    //----------------------------------------------------------------
    public function __call( $func, $args )
    {
        return call_user_func_array(array($this->db, $func), $args);
    }
    
    //----------------------------------------------------------------
    // Prepare a statement
    //----------------------------------------------------------------
    public function prepare( $sql='' )
    {
        /*
        if( is_object($this->query_active_reference) )
        {
            $this->query_active_reference->closeCursor();
            $this->query_active_reference = NULL;
        }
        */
        $this->prepares[] = $sql;
        return new db_statements( $this->db->prepare( $sql ) );
    }
    
    //----------------------------------------------------------------
    // Simple Query with closeCursor() !
    //----------------------------------------------------------------
    public function simple_query($sql='', $args = array() )
    {
        
        $res = $this->prepare( $sql );
        $res->execute( $args );
        $res->closeCursor();
        $res = NULL;
        
        return $res;
    }
    
    //----------------------------------------------------------------
    // Deliver query to DB
    // Increase counter
    //----------------------------------------------------------------
    public function query( $sql='' )
    {
        
        $this->query_counter++;
        $this->queries[] = $sql;
        $res = $this->db->query($sql);
        
        return $res;
    }
    
    //----------------------------------------------------------------
    // Exec
    // Increase counter
    //----------------------------------------------------------------
    public function exec($sql='' )
    {
        
        $this->exec_counter++;
        $res = $this->db->exec($sql );
        
        $this->execs[] = $sql;
        
        return $res;
    }
    
}

//----------------------------------------------------------------
// Db Statements Wrapper
//----------------------------------------------------------------
class db_statements
{
    public $db_statement;
    
    //----------------------------------------------------------------
    // Constructor
    //----------------------------------------------------------------
    function __construct($db_pre_s)
    {
        $this->db_statement = $db_pre_s; 
    }
    
    //----------------------------------------------------------------
    // Non-existing methods
    //----------------------------------------------------------------
    function __call($func, $args)
    {
        return call_user_func_array(array($this->db_statement, $func), $args);        
    }
    
    //----------------------------------------------------------------
    // $stmt->execute
    //----------------------------------------------------------------
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
        var_dump($this->db_statement);
        $res = call_user_func(array($this->db_statement, 'execute'), $args);

        if ( $res )
        {
            $db->query_active_reference = $this;
            return $res;
        }
        return $res;
    }   
}
?>