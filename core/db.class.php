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
    // Number of exec's
    //----------------------------------------------------------------
    public $exec_counter = 0;
    
    //----------------------------------------------------------------
    // Number of performed statements
    //----------------------------------------------------------------
    public $query_counter = 0;
    
    //----------------------------------------------------------------
    // Number of prepared statements
    //----------------------------------------------------------------
    public $prepared_counter = 0;
    
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
    // Constructor
    // Create DB Object
    // Set counters to zero
    //----------------------------------------------------------------
    public function __construct($dsn, $user=NULL, $pass=NULL, $driver_options=NULL)
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
    
    //----------------------------------------------------------------
    // Call forward to DB
    //----------------------------------------------------------------
    public function __call($func, $args)
    {
        return call_user_func_array(array($this->db, $func), $args);
    }
    
    //----------------------------------------------------------------
    // Prepare a statement
    //----------------------------------------------------------------
    public function prepare($sql='' )
    {
        
        $res = $this->db->prepare( $sql );
        $this->prepared_counter++;
        
        $this->prepares[] = $sql;
        
        return $res;
    }
    
    //----------------------------------------------------------------
    // Simple Query with closeCursor() !
    //----------------------------------------------------------------
    public function simple_query($sql='', $args = '' )
    {
        
        $res = $this->prepare( $sql );
        $this->prepared_counter++;
        
        $res->execute($args);
        $this->exec_counter++;
        
        $res->closeCursor();
        
        $this->prepares[] = $sql;
        $this->queries[] = $sql;
        
        return $res;
    }
    
    //----------------------------------------------------------------
    // Deliver query to DB
    // Increase counter
    //----------------------------------------------------------------
    public function query($sql='' )
    {
        
        $this->queries_counter++;
        $res = $this->db->query($sql);
        
        $this->queries[] = $sql;
        
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
?>