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
* Class db 
* 
* PDO Init Wrapper! $db = new PDO;
* 
* added Functions
* - Execute Counter 
* - Statement Counter
*/
class db
{
	//----------------------------------------------------------------
	// DB Object
	//----------------------------------------------------------------
	protected $db;

	//----------------------------------------------------------------
	// Number of executed queries
	//----------------------------------------------------------------
	public $exec_counter;

	//----------------------------------------------------------------
	// Number of performed statements
	//----------------------------------------------------------------
	public $statements_counter;

	//----------------------------------------------------------------
	// Queries Array
	//----------------------------------------------------------------
	public $queries = array();

	//----------------------------------------------------------------
	// Prepare Statements Array
	//----------------------------------------------------------------
	public $prepare_statements = array();
		
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
			$this->db = new PDO($dsn, $user, $pass, $driver_options);
		
			// error 
			$this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			// table-names in lower-case
			$this->db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		}

		catch (PDOException $e)
		{
			$error->show( $lang->t('Database Error'), $e->getMessage(), 1 );
		}

		$this->exec_counter = 0;
		$this->statements_counter = 0;
	}

	//----------------------------------------------------------------
	// SELECT Statement
	// Return associated array
	//----------------------------------------------------------------
	public function select( $cols='*', $from='', $where='', $more='' )
	{
		if ( is_array( $cols ) )
		{
			$sql = 'SELECT ' . $cols['SELECT'] . ' FROM '. DB_PREFIX . $cols['FROM'] . ' WHERE ' . $cols['WHERE'] . ' ' . $cols['MORE'];

		}
		else
		{
			$sql = 'SELECT ' . $cols . ' FROM '. DB_PREFIX . $from . ' WHERE ' . $where . ' ' . $more;
		}

		$this->last_sql = $sql;

		return $sql;
	}

	//----------------------------------------------------------------
	// INSERT Statement
	// Return PDOStatement Object
	//----------------------------------------------------------------
	public function insert( $table='', $data = array(), $more='' )
	{
		global $error, $lang;
		
		if ( is_array( $data ) )
		{
			$ins_sql .= '(';
			foreach ( $data as $key => $value)
			{
				$ins_sql .= "`$key`,";	
			}
			$ins_sql = preg_replace( "/,$/" , "" , $ins_sql  );
			$ins_sql .= ') VALUES (';
			foreach ( $data as $key => $value)
			{
				$ins_sql .= is_int($value) ? "$value," : "'$value',";	
			}
			$ins_sql = preg_replace( "/,$/" , "" , $ins_sql  );
			$ins_sql .= ')';
			
			try
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' '. $ins_sql . ' ' . $more;
				$res = $this->prepare( $sql );
				$res->execute();
				$return_value = $this->db->lastInsertId();
			}

			catch (PDOException $e)
			{
				$is_error = true;
				$message = $e->getMessage();
			}

			$this->queries[] = $sql;
			
			if($is_error)
			{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }
		}
			
		$res->closeCursor();
		return $return_value;
	}
	
	//----------------------------------------------------------------
	// UPDATE Statement
	// Return PDOStatement Object
	//----------------------------------------------------------------
	public function update( $table='', $where='', $data = array(), $more='' )
	{
		global $error, $lang;
		
		if ( is_array( $data ) )
		{
			$upd_sql = '';
			foreach ( $data as $key => $value)
			{
				$upd_sql .= is_int($value) ? "`$key` = $value," : "`$key` = '$value',";
			}
			$upd_sql = preg_replace( "/,$/" , "" , $upd_sql  );
			
			try
			{
				$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET '. $upd_sql . ' WHERE ' . $where . ' ' . $more;
				$res = $this->prepare( $sql );
				$res->execute();
			}

			catch (PDOException $e)
			{
				$is_error = true;
				$message = $e->getMessage();
			}

			$this->queries[] = $sql;
			
			if($is_error)
			{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }
		}
		
		$return_value = $res->rowCount();
		$res->closeCursor();
		return $return_value;
	}

	//----------------------------------------------------------------
	// DELETE Statement
	// Return PDOStatement Object
	//----------------------------------------------------------------
	public function delete( $table='', $where='', $more='' )
	{
		global $error, $lang;
				
		try
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE ' . $where . ' ' . $more;
			$res = $this->prepare( $sql );
			$res->execute();
		}

		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $sql.'<br>'.$e->getMessage(), 1 ); }
		
		$return_value = $res->rowCount();
		$res->closeCursor();
		return $return_value;
	}
			
	//----------------------------------------------------------------
	// Fetch Array (PDO::FETCH_ASSOC)
	//----------------------------------------------------------------
	public function fetch_array( $sql='', $option = PDO::FETCH_ASSOC )
	{
		global $error, $lang;
		
		try
		{
			if($sql!='')
			{
				$res = $this->prepare( $sql );
				$res->execute();
				$return_value = $res->fetch($option);
				$res->closeCursor();
			}
			else
			{
				$res = $this->prepare( $this->last_sql );
				$res->execute();
				$return_value = $res->fetch($option);	
				$res->closeCursor();
			}
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $sql.'<br>'.$e->getMessage(), 1 ); }
		
		return $return_value;	
	}
	
	//----------------------------------------------------------------
	// Fetch ALL
	//----------------------------------------------------------------
	public function fetch_all( $sql='', $options = PDO::FETCH_BOTH )
	{
		try
		{
			if($sql!='')
			{
				$res = $this->prepare( $sql );
				$res->execute();
				$return_value = $res->fetchAll($option);
				$res->closeCursor();
			}
			else
			{
				$res = $this->prepare( $this->last_sql );
				$res->execute();
				$return_value = $res->fetchAll($option);	
				$res->closeCursor();
			}
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $sql.'<br>'.$e->getMessage(), 1 ); }
		
		return $return_value;	
	}	
	
	//----------------------------------------------------------------
	// Affected Rows (rowCount + SELECT COUNT(*) emulation)
	//----------------------------------------------------------------
	public function affected_rows( $sql='' )
	{
		global $error,$lang;
		
		try
		{
			if( is_object ($sql) )
			{
				$return_value = $sql->rowCount();	
			}
			else if ( is_object ($this->last_sql) )
			{
				$return_value = $this->last_sql->rowCount();
			}
			else if($sql!='')
			{
				$res = $this->prepare( $sql );
				$res->execute();
				$return_value = $res->rowCount()==0 ? count($res->fetchAll()) : 0;;
				$res->closeCursor();
			}
			else
			{
				$res = $this->prepare( $this->last_sql );
				$res->execute();
				$return_value = $res->rowCount()==0 ? count($res->fetchAll()) : 0;;	
				$res->closeCursor();
			}	
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $sql.'<br>'.$e->getMessage(), 1 ); }
		
		return $return_value;		
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
	public function prepare( $sql='' )
	{
		$this->exec_counter++;
		$this->statements_counter++;
		
		$res = $this->db->prepare( $sql );
		
		$this->prepare_statements[] = $sql;

		return $res;
	}

	//----------------------------------------------------------------
	// Simple Query with closeCursor() !
	//----------------------------------------------------------------
	public function simple_query( $sql='' )
	{
		global $error, $lang;
		
		try
		{
			$this->exec_counter++;
			$this->statements_counter++;			
			$res = $this->prepare( $sql );
			$res->execute();
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;

		if($is_error)
		{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }
		
		$res->closeCursor();
		return $res;
	}
	
	//----------------------------------------------------------------
	// Deliver query to DB
	// Increase counters
	//----------------------------------------------------------------
	public function query( $sql='' )
	{
		global $error, $lang;
		
		try
		{
			$this->exec_counter++;
			$this->statements_counter++;			
			$res = $this->db->query($sql);
		}
		
		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;

		if($is_error)
		{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }

		return $res;
	}

	//----------------------------------------------------------------
	// Execute a statement
	//----------------------------------------------------------------
	public function exec( $sql='' )
	{
		global $error, $lang;

		try
		{
			$this->exec_counter++;
			$res = $this->db->exec( $sql );
		}

		catch (PDOException $e)
		{
			$is_error = true;
			$message = $e->getMessage();
		}

		$this->queries[] = $sql;
		
		if($is_error)
		{ $error->show( $lang->t('Database Error'), $e->getMessage(), 1 ); }
		
		return $res;
	}

}



   