<?php
   /**
	* Clansuite - just an eSports CMS
	* Jens-André Koch © 2005 - onwards
	* http://www.clansuite.com/
	*
	* This file is part of "Clansuite - just an eSports CMS".
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
	* @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
	*
	* @author     Jens-André Koch <vain@clansuite.com>
	* @copyright  Jens-André Koch (2005 - onwards)
	*
	* @link       http://www.clansuite.com
	* @link       http://gna.org/projects/clansuite
	* @since      File available since Release 0.2
	*
	* @version    SVN: $Id$
	*/

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Core Class for the Initialization of phpDoctrine
 *
 * The phpDoctrine library provides a database abstraction layer (DAL)
 * as well as object-relational mapping (ORM)
 * with drivers for every PDO-supported database:
 *
 *    - FreeTDS / Microsoft SQL Server / Sybase
 *    - Firebird/Interbase 6
 *    - Informix
 *    - Mysql
 *    - Oracle
 *    - Odbc
 *    - PostgreSQL
 *    - Sqlite
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Doctrine
 */
class Clansuite_Doctrine
{
	private $config;        # holds a config instance
	protected $manager;     # holds instance of doctrine manager
	protected $locator;     # holds instance of doctrine locator
	protected $connection;  # holds instance of database connection
	public $profiler;       # holds instance of doctrine connection profiler

	function __construct(Clansuite_Config $config)
	{
		$this->config = $config; # set config instance

		# Load DBAL
		$this->doctrine_initialize();

		# Db Connection
		$this->prepareDbConnection();

		# if Clansuite Debug Mode enabled
		if ( defined('DEBUG') )
		{
			# activate Doctrine Debug also
			Doctrine::debug(true);
		}
	}

	/**
	 * Doctrine Initialize
	 *
	 * 1. Loads the compiled (if exists) or normal Doctrine Base Class
	 * 2. registers autoload
	 * 3. Prepares a connection to the Database
	 */
	private function doctrine_initialize()
	{
		if (!class_exists('Doctrine')) // prevent redeclaration
		{
			# Require compiled or normal Library
			if (is_file( ROOT_LIBRARIES . 'doctrine/Doctrine.compiled.php'))
			{
				require ROOT_LIBRARIES .'doctrine/Doctrine.compiled.php';
			}
			elseif(is_file( ROOT_LIBRARIES . 'doctrine/Doctrine.php')) # require the normal Library
			{
				require ROOT_LIBRARIES .'doctrine/Doctrine.php';
			}
			else
			{
				throw new Clansuite_Exception('Doctrine could not be loaded. Check Libraries Folder.', 100);
			}

			# Register the Doctrine autoloader
			spl_autoload_register(array('Doctrine', 'autoload'));
		}
	}

	/**
	 * prepareDbConnection
	 *
	 * This prepares a Database Connection via Doctrine_Manager
	 * It's a lazy-connect, so it connects only when needed.
	 * That will safe ressources.
	 *
	 * @link DNS Types on Doctrine Chapter 4.1.
	 */
	private function prepareDbConnection()
	{
		/**
		 * Construct the Data Source Name (DSN)
		 *
		 * Examples:
		 * $dsn = 'mysql://clansuite:toop@localhost/clansuite';
		 * $dsn = 'mysql://databaseuser:databasepassword@servername/databasename';
		 *
		 * Debugdisplay
		 * echo 'Doctrine DSN: '.$dsn; exit();
		 */
		$dsn = sprintf('%s://%s:%s@%s/%s',
					   $this->config['database']['db_type'],
					   $this->config['database']['db_username'] ,
					   $this->config['database']['db_password'],
					   $this->config['database']['db_host'],
					   $this->config['database']['db_name']
		);

		/**
		 * Setup phpDoctrine ConnectionObject for LATER Connection
		 */
		$this->manager = Doctrine_Manager::getInstance();
		if (count($this->manager) === 0)
		{
			#$this->connection = $this->manager->openConnection(new PDO('sqlite::memory:'));
			#Doctrine_Manager::getInstance()->connection($dsn, $this->config['database']['db_name']);
			$this->connection = $this->manager->connection($dsn, $this->config['database']['db_name']);
		} else
		{
			#Doctrine_Manager::getInstance()->getCurrentConnection();
			$this->connection = $this->manager->getCurrentConnection();
		}

		# Get Doctrine Locator and set ClassPrefix
		$this->locator = Doctrine_Locator::instance();
		$this->locator->setClassPrefix('Clansuite_');

        /**
         * Set Cache Driver
         */
        # if we have APC available and are not in debug mode, then try to cache doctrine queries
		if(extension_loaded('apc') and (defined('DEBUG') == false) and
		   isset($this->config['database']['db_cache']) and ('APC' == $this->config['database']['db_cache']))
		{
		    $cachedriver = new Doctrine_Cache_Apc();
			$this->manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cachedriver);

			# set the lifespan as one hour (60 seconds * 60 minutes = 1 hour = 3600 secs)
		    $this->manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 3600);
		}

		/**
		 * Setup phpDoctrine Attributes for that later Connection
		 */

		# Set portability for all rdbms = default
		#$manager->setAttribute('portability', Doctrine::PORTABILITY_ALL);
		# Changing the database naming convention by adding
		# TBLNAME: clansuite.DB_PREFIX_tablename
		$this->manager->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, DB_PREFIX ."%s");
		$this->manager->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
		#$manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);

		/**
		 * Load Models (automatic + lazy loading)
		 *
		 * "Aggressive - It finds all .php files in a given path recursively and
		 * performs a require_once() on each file. Your files can be in subfolders, and
		 * the files can include multiple models. It is very flexible but the downside
		 * is that it will require_once() all files.
		 *
		 * Conservative - It finds all .php files in a given path recursively and
		 * builds an array of className => /path/to/file. The className is parsed from
		 * the name of the file, so each file must contain only one class and the file
		 * must be named after the class inside of it. This array is then referenced in
		 * Doctrine::autoload() and used to load models when they are asked for."
		 *
		 * Quote from Johnatan Wage on http://groups.google.com/group/doctrine-user
		 */
		$this->manager->setAttribute('model_loading', 'conservative');

		# define the models directory
		# this will NOT require the .php files found
		Doctrine::loadModels( ROOT . '/myrecords/' );

		# Debug Listing of all loaded Doctrine Models
		#$models = Doctrine::getLoadedModels();
		#var_dump($models);

		#$path = Doctrine::getPath();
		#var_dump($path);

		# DBMS Portability All is Doctrines default, therefore commented out.
		# $manager->setAttribute(Doctrine::ATTR_PORTABILITY, Doctrine::PORTABILITY_ALL);

		# Validate All
		#$this->manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);

		# Export All
		$this->manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);

		# Identifier Quoting
		# In general, quoting make things worse.
		# Only one problem solved by quoting: usage of reserved words as field names.
		# We won't use reserved words - therefore this attribute is disabled for now.
		$this->manager->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, false);

		# Set Connection Listener for Profiling
		$this->profiler = new Doctrine_Connection_Profiler();
		$this->connection->setListener($this->profiler);
	}

	/**
	 * Get for DB-Connection
	 */
	public static function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Returns Doctrine's connection profiler
	 *
	 * @return Doctrine_Connection_Profiler
	 */
	public function getProfiler()
	{
			return $this->profiler;
	}


	/**
	 * Displayes all Doctrine Querys with profiling Informations
	 * @todo This is debug output + direct output breaks the abstraction
	 */
	public function displayProfilingHTML()
	{
		$query_count = 0;
		$time = 0;
		echo "<p><strong>Doctrine Queries</strong>

		</p>";
		echo '<table width="95%" border="1">';
		echo '<tr style="font-weight: bold;"><td>Command</td><td>Time</td><td width="50%">Query with placeholder (?) for parameters</td><td width="40%">Parameters</td></tr>';
		foreach ( $this->profiler as $event )
		{
			/*if ($event->getName() != 'execute')
			{
				continue;
			}
			*/
			$query_count++;
			echo "<tr>";
			$time += $event->getElapsedSecs();
			echo "<td>" . $event->getName() . "</td><td>" . sprintf ( "%f" , $event->getElapsedSecs() ) . "</td>";
			echo "<td>" . $event->getQuery() . "</td>";
			$params = $event->getParams();
			if ( !empty($params))
			{
				  echo "<td>";
				  echo join(', ', $params);
				  echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo "<br />$query_count Queries in " . sprintf("%2.5f", $time) . " secs.<br>\n";
	}
}
?>