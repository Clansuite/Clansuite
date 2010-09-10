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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for the Initialization of phpDoctrine
 *
 * The phpDoctrine library provides a database abstraction layer (DAL)
 * as well as object-relational mapping (ORM) with drivers for every PDO supported database:
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
    /**
     * @var Array of Clansuite Main Config.
     */
    private $config;

    /**
     * @var Doctrine_Manager instance.
     */
    protected $manager;

    /**
     * @var Doctrine_Connection instance
     */
    protected $connection;

    /**
     * @var Doctrine_Profiler Holds an instance of the Doctrine Connection Profiler.
     */
    public $profiler;

    function __construct(array $config)
    {
        # set config instance
        $this->config = $config;

        # Load DBAL
        $this->doctrine_initialize();

        # Db Connection
        $this->prepareDbConnection();

        # if Clansuite Debug Mode enabled
        if ( defined('DEBUG') and DEBUG === true)
        {
            # activate Doctrine Debug also
            Doctrine_Core::debug(true);
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
        # prevent redeclaration
        if(false === class_exists('Doctrine_Core', false))
        {
            $doctrine_compiled = ROOT_LIBRARIES . 'doctrine' . DS . 'Doctrine.compiled.php';

            # Require compiled or normal Library
            if(is_file($doctrine_compiled) === true)
            {
                include_once $doctrine_compiled;
            }
            elseif(is_file(ROOT_LIBRARIES . 'doctrine/Doctrine.php') === true)
            {
                # require the normal Library
                include_once ROOT_LIBRARIES . 'doctrine/Doctrine.php';
            }
            else
            {
                throw new Clansuite_Exception('Doctrine could not be loaded. Check Libraries Folder.', 100);
            }

            # Register the Doctrine autoloader, ensure it's not already registered
            if(false === in_array(array('Doctrine_Core', 'autoload'), spl_autoload_functions()))
            {
                spl_autoload_register(array('Doctrine_Core', 'autoload'));
                spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
            }

            /**
             * automatically compile doctrine to one file, but only compile with the mysql driver
             * so that the next time Doctrine.compiled.php is found
             */
            if(is_file($doctrine_compiled) === false)
            {
                Doctrine::compile($doctrine_compiled, array('mysql'));
            }

            unset($doctrine_compiled);
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
                       $this->config['type'],
                       $this->config['username'] ,
                       $this->config['password'],
                       $this->config['host'],
                       $this->config['name']
        );

        # Setup Doctrine Connection
        $this->manager = Doctrine_Manager::connection($dsn, $this->config['name']);

        /**
         * test connection
         *
         * @todo Problem: testing the database connection destroys the lazy-connect mode of doctrine.
         */
        try
        {
            $this->manager->connect();
        }
        catch(Doctrine_Connection_Exception $e)
        {
            $exception_message = 'Doctrine Connection could not be established. Check Data Source Name.
                                  Ensure that Databasename, Tablename, Username and Password are correct!
                                  <br />'.$e->getMessage();

            throw new Clansuite_Exception($exception_message, 1);
        }

        /**
         * Set Cache Driver for Doctrine - but not when we are debugging
         */
        if(defined('DEBUG') == false)
        {
            $this->initDoctrineCacheDriver();
        }

        /**
         * Setup phpDoctrine Attributes for that later Connection
         */

        /**
         * DEFINE -> Database Prefix
         */
        define('DB_PREFIX', $this->config['prefix'] );

        # Set portability for all rdbms = default
        #$manager->setAttribute('portability', Doctrine_Core::PORTABILITY_ALL);

        # Changing the database naming convention by adding
        # TBLNAME: clansuite.DB_PREFIX_tablename
        $this->manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, DB_PREFIX . '%s');
        $this->manager->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

        # Load Tables (with custom methods) automatically
        $this->manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        # Enable automatic accessor overriding
        $this->manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

        # Enables the auto freeing of query objects after execution
        $this->manager->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true);

        /**
         * Sets Charset and Collation globally on Doctrine_Manager instance
         */
        # the following cmd is more direct and just a substitute for setCollate()
        $this->manager->setAttribute(Doctrine_Core::ATTR_DEFAULT_TABLE_COLLATE, 'utf8_unicode_ci');
        $this->manager->setCharset('utf8');

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
         * Doctrine_Core::autoload() and used to load models when they are asked for."
         *
         * Quote from Johnatan Wage on http://groups.google.com/group/doctrine-user
         */
        $this->manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
        #$this->manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);

        /**
         * Setup Doctrine Models Directory
         *
         * Register the Doctrine models by defining the models directory for the autoloader.
         * Note: this will NOT require the .php files found. Doctrine is able to lazy-load them later on.
         */

        # @todo this is for Doctrine2
        # Doctrine_Core::setModelsDirectory(ROOT . 'records');
        # Doctrine_Core::setModelsDirectory(ROOT_MOD); # somewhere beneath modules folder, rest via autoload

        #Doctrine_Core::loadModels(ROOT . 'myrecords/generated', Doctrine_Core::MODEL_LOADING_CONSERVATIVE );
        Doctrine_Core::loadModels(ROOT . 'myrecords/', Doctrine_Core::MODEL_LOADING_CONSERVATIVE );

        # Debug Listing of all loaded Doctrine Models
        #$models = Doctrine_Core::getLoadedModels();
        #var_dump($models);

        #$path = Doctrine_Core::getPath();
        #var_dump($path);

        # DBMS Portability All is Doctrines default, therefore commented out.
        # $manager->setAttribute(Doctrine_Core::ATTR_PORTABILITY, Doctrine_Core::PORTABILITY_ALL);

        # Validate All
        $this->manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);

        # Export All
        $this->manager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);

        # Identifier Quoting
        # In general, quoting make things worse.
        # Only one problem solved by quoting: usage of reserved words as field names.
        # We won't use reserved words - therefore this attribute is disabled for now.
        $this->manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);

        # Set default primary key name for tables as 'id' being an integer with length of 4 bytes
        $this->manager->setAttribute(Doctrine_Core::ATTR_DEFAULT_IDENTIFIER_OPTIONS,
            array('name' => 'id', 'type' => 'integer', 'length' => 4));
        /**
         * This changes the column identifier from 'id' to 'tablename_id',
         * where %s stands for tablename. table news, column "id" becomes "news_id".
         */
        #array('name' => '%s_id', 'type' => 'string', 'length' => 30));

        # Set Connection Listener for Profiling - in case we are debugging
        if(DEBUG == 1)
        {
            $this->initDoctrineProfiler();
        }
    }

    /**
     * This initializes the Doctrine Profiler
     */
    public function initDoctrineProfiler()
    {
        include ROOT_CORE . 'debug/doctrineprofiler.core.php';
        Clansuite_Doctrine_Profiler::attachProfiler();
    }

    /**
     * This initializes the Doctrine Cache Driver by setting the correct attribute.
     * The cachedriver attribute depends on the main configuration setting.
     * @see $this->config['cache']
     */
    public function initDoctrineCacheDriver()
    {
        $cacheDriver = '';
        # consider setting lifespan by config later, for now hardcoded
        # as one hour (60 seconds * 60 minutes = 1 hour = 3600 secs)
        $cacheLifespan = '3600';

        if(isset($this->config['cache']))
        {
            if(('APC' == $this->config['cache']) and extension_loaded('apc'))
            {
                $cacheDriver = new Doctrine_Cache_Apc();
            }

            /**
             * @todo conditionals for other drivers: memcached, etc.
             */

            /**
             * Set Doctrine Attributes
             */
            $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cacheDriver);
            $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, $cacheLifespan);
        }
    }

    /**
     * Get for DB-Connection
     */
    public static function getConnection()
    {
        return $this->connection;
    }
}
?>