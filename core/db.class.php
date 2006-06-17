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
	die( 'You are not allowed to view this page statically.' );	
}

/**
* Class Db 
* 
* PDO Init Wrapper! $Db = new PDO;
* 
* added Functions
* - Execute Counter 
* - Statement Counter
*/
class Db
{
	//----------------------------------------------------------------
	// DB Object
	//----------------------------------------------------------------
	protected $Db;

	//----------------------------------------------------------------
	// Number of executed queries
	//----------------------------------------------------------------
	public $executesCounter;

	//----------------------------------------------------------------
	// Number of performed statements
	//----------------------------------------------------------------
	public $statementsCounter;

	//----------------------------------------------------------------
	// Queries Array
	//----------------------------------------------------------------
	public $queries = array();
	
	//----------------------------------------------------------------
	// Constructor
	// Create DB Object
	// Set counters to zero
	//----------------------------------------------------------------
	public function __construct($dsn, $user=NULL, $pass=NULL, $driver_options=NULL)
	{
		global $error, $lang;
		
		try
		{
			$this->Db = new PDO($dsn, $user, $pass, $driver_options);
		
			// error 
			$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			// table-names in lower-case
			$this->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		}

		catch (PDOException $e)
		{
			$error->show( $lang->t('Database Error'), $e->getMessage(), 1 );
		}

		$this->executesCounter = 0;
		$this->statementsCounter = 0;
	}

	//----------------------------------------------------------------
	// SELECT Statement
	// Return associated array
	//----------------------------------------------------------------
	public function select( $cols='*', $from, $where='', $more='' )
	{
		$return_value = array();
		
		$res = $this->query('SELECT ' . $cols . ' FROM '. DB_PREFIX . $from . ' WHERE ' . $where . ' ' . $more);	
		while( $row = $res->fetch(PDO::FETCH_ASSOC) )
		{
			array_push( $return_value, $row );	
		}
		return $return_value;
	}
	
	//----------------------------------------------------------------
	// Call forward to DB
	//----------------------------------------------------------------
	public function __call($func, $args)
	{
	   return call_user_func_array(array(&$this->Db, $func), $args);
	}

	//----------------------------------------------------------------
	// Prepare a query
	//----------------------------------------------------------------
	public function prepare()
	{
	   $this->statementsCounter++;
	  
	   $args = func_get_args();
	   $DbStatements = call_user_func_array(array(&$this->Db, 'prepare'), $args);
	  
	   return new DbStatements($this, $DbStatements);
	}

	//----------------------------------------------------------------
	// Deliver query to DB
	// Increase counters
	//----------------------------------------------------------------
	public function query()
	{
		global $error, $lang;
		try
		{
			$this->executesCounter++;
			$this->statementsCounter++;

			$args = func_get_args();
			$DbStatements = call_user_func_array(array(&$this->Db, 'query'), $args);
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		foreach ($args as $q)
		{
			$this->queries[] = $q;
		}

		if($is_error)
		{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }

		return new DbStatements($this, $DbStatements);
	}

	//----------------------------------------------------------------
	// Execute a statement
	//----------------------------------------------------------------
	public function exec()
	{
		global $error, $lang;
		
		try
		{
		   $this->executesCounter++;
		  
		   $args = func_get_args();
		   var_dump($args);
		   return call_user_func_array(array(&$this->Db, 'execute'), $args);
		}

		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }
	}

}

//----------------------------------------------------------------
// Deliver extende object
//----------------------------------------------------------------
class DbStatements implements IteratorAggregate
{

	/**
	* DbStatements Object
	* @var object $DbStatements
	*/
	protected $DbStatements;

	/**
	* Db Connection Object
	* @var object $Db
	*/
	protected $Db;

	/**
	* __construct
	*
	* @param $Db, $DbStatements 
	*/
	public function __construct($Db, $DbStatements) {
	   $this->Db = $Db;
	   $this->DbStatements = $DbStatements;
	}

	/**
	* __call : Allgemeine Funktions und Parameter Weiterleitung in DbStatements
	* @param $func, $args
	* @return 
	*/
	public function __call($func, $args) {
	   return call_user_func_array(array(&$this->DbStatements, $func), $args);
	}

	/**
	* bindColumn
	* 
	* @param $column
	* @param &$param
	* @param $type
	*/
	public function bindColumn($column, &$param, $type=NULL) {
	   if ($type === NULL)
	       $this->DbStatements->bindColumn($column, $param);
	   else
	       $this->DbStatements->bindColumn($column, $param, $type);
	}

	/**
	* bindParam
	*
	* @param $column
	* @param &$param
	* @param $type
	* @return 
	*/
	public function bindParam($column, &$param, $type=NULL) {
	   if ($type === NULL)
	       $this->DbStatements->bindParam($column, $param);
	   else
	       $this->DbStatements->bindParam($column, $param, $type);
	}
	/**
	* execute
	* a. exec Counter in Db erhöhen
	* b. weiterleiten an DbStatements mit execute und parametern 
	* @return of Function DbStatements $func 'execute' + $args
	*/
	public function execute() {
	   $this->Db->executesCounter++;
	   $args = func_get_args();
	   return call_user_func_array(array(&$this->DbStatements, 'execute'), $args);
	}
	/**
	* __get : Rückgabe liefern
	* @param $property
	* @return return $this->DbStatements->$property
	*/
	public function __get($property) {
	   return $this->DbStatements->$property;
	}

	/**
	* holt das Objekt
	* @return $this->DbStatements
	*/
	public function getIterator() {
	   return $this->DbStatements;
	}
}
   
//----------------------------------------------------------------
// DB Error Handler
//----------------------------------------------------------------
class DbError extends Db
{
	// Fetch extended error information associated with the last operation
	//echo "\nPDO::errorInfo():\n";
	//print_r($err->errorInfo());
}


   