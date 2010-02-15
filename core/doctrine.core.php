<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 * as well as object-relational mapping (ORM) with drivers for every PDO-supported database:
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
        # set config instance
        $this->config = $config;

        # Load DBAL
        $this->doctrine_initialize();

        # Db Connection
        $this->prepareDbConnection();

        # if Clansuite Debug Mode enabled
        if ( defined('DEBUG') )
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
        if (!class_exists('Doctrine',false))
        {
            $doctrine_compiled = ROOT_LIBRARIES . 'doctrine'.DS.'Doctrine.compiled.php';

            # Require compiled or normal Library
            if (is_file($doctrine_compiled))
            {
                require $doctrine_compiled;
            }
            elseif(is_file( ROOT_LIBRARIES . 'doctrine/Doctrine.php'))
            {
                # require the normal Library
                require ROOT_LIBRARIES .'doctrine/Doctrine.php';
            }
            else
            {
                throw new Clansuite_Exception('Doctrine could not be loaded. Check Libraries Folder.', 100);
            }

            # Register the Doctrine autoloader
            spl_autoload_register(array('Doctrine_Core', 'autoload'));
            spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

            /**
             * automatically compile doctrine to one file, but only compile with the mysql driver
             * so that the next time Doctrine.compiled.php is found
             * @link compile but only use only compile the mysql driver
             * @todo add the dbtype from config as the driver to compile
             */
            if (is_file($doctrine_compiled) == false)
            {
                # @todo Doctrine_Core::compile seems to be broken
                #Doctrine_Core::compile($doctrine_compiled, array('mysql'));
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
                       $this->config['database']['type'],
                       $this->config['database']['username'] ,
                       $this->config['database']['password'],
                       $this->config['database']['host'],
                       $this->config['database']['name']
        );

        /**
         * Setup phpDoctrine ConnectionObject for LATER Connection
         */
        $this->manager = Doctrine_Manager::getInstance();
        if (count($this->manager) === 0)
        {
            #$this->connection = $this->manager->openConnection(new PDO('sqlite::memory:'));
            #Doctrine_Manager::getInstance()->connection($dsn, $this->config['database']['name']);
            $this->connection = $this->manager->connection($dsn, $this->config['database']['name']);
        }
        else
        {
            #Doctrine_Manager::getInstance()->getCurrentConnection();
            $this->connection = $this->manager->getCurrentConnection();
        }

        /**
         * test connection
         */
        try
        {
            $this->connection->connect();
        }
        catch(Doctrine_Connection_Exception $e)
        {
            $exception_message = 'Doctrine Connection could not be established. Check Data Source Name.
                                  Ensure that Databasename, Tablename, Username and Password are correct!
                                  <br />'.$e->getMessage();

            throw new Clansuite_Exception($exception_message, 1);
        }

        # Get Doctrine Locator and set ClassPrefix
        $this->locator = Doctrine_Locator::instance();
        $this->locator->setClassPrefix('Clansuite_');

        /**
         * Set Cache Driver
         */
        # if we have APC available and are not in debug mode, then try to cache doctrine queries
        if(extension_loaded('apc') and (defined('DEBUG') == false) and
           isset($this->config['database']['cache']) and ('APC' == $this->config['database']['cache']))
        {
            $cachedriver = new Doctrine_Cache_Apc();
            $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cachedriver);

            # set the lifespan as one hour (60 seconds * 60 minutes = 1 hour = 3600 secs)
            $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 3600);
        }

        /**
         * Setup phpDoctrine Attributes for that later Connection
         */

        /**
         * DEFINE -> Database Prefix
         */
        define('DB_PREFIX', $this->config['database']['prefix'] );

        # Set portability for all rdbms = default
        #$manager->setAttribute('portability', Doctrine_Core::PORTABILITY_ALL);

        # Changing the database naming convention by adding
        # TBLNAME: clansuite.DB_PREFIX_tablename
        $this->manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, DB_PREFIX ."%s");
        $this->manager->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

        # Load Tables (with custom methods) automatically
        $this->manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        # Enable automatic accessor overriding
        $this->manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

        # Enables the auto freeing of query objects after execution
        $this->manager->setAttribute('auto_free_query_objects', true);

        /**
         * Set default added auto-id
         * This changes the column identifier from 'id' to 'tablename_id',
         * where %s stands for tablename. table news, column "id" becomes "news_id".
         */
        #$this->manager->setAttribute(Doctrine_Core::ATTR_DEFAULT_IDENTIFIER_OPTIONS,
        #array('name' => '%s_id', 'type' => 'string', 'length' => 30));

        /**
         * Sets Charset and Collation globally on Doctrine_Manager instance
         */
        $this->manager->setCollate('utf8_unicode_ci');
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

        #Doctrine_Core::loadModels(ROOT . '/myrecords/generated');
        Doctrine_Core::loadModels( ROOT . '/myrecords/' );

        # Debug Listing of all loaded Doctrine Models
        #$models = Doctrine_Core::getLoadedModels();
        #var_dump($models);

        #$path = Doctrine_Core::getPath();
        #var_dump($path);

        # DBMS Portability All is Doctrines default, therefore commented out.
        # $manager->setAttribute(Doctrine_Core::ATTR_PORTABILITY, Doctrine_Core::PORTABILITY_ALL);

        # Validate All
        #$this->manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);

        # Export All
        $this->manager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);

        # Identifier Quoting
        # In general, quoting make things worse.
        # Only one problem solved by quoting: usage of reserved words as field names.
        # We won't use reserved words - therefore this attribute is disabled for now.
        $this->manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);

        # Set Connection Listener for Profiling if we are in DEBUG MODE
        if(DEBUG == 1)
        {
            $this->attachProfiler();
            register_shutdown_function(array($this,'shutdown'));
        }
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
        return $this->connection->getListener();
    }

    /**
     * Attached the Doctrine Profiler as Listener to the connection
     */
    public function attachProfiler()
    {
        # instantiate Profiler and attach to doctrine connection
        $this->connection->setListener(new Doctrine_Connection_Profiler);
    }

    /**
     * Displayes all Doctrine Querys with profiling Informations
     *
     * Because this is debug output, it's ok that direct output breaks the abstraction.
     * @return Direct HTML Output
     */
    public function displayProfilingHTML()
    {
        /**
         * @var int total number of database queries performed
         */
        $query_counter = 0;
        /**
         * @var int time in seconds, counting the elapsed time for all queries
         */
        $time = 0;

        echo '<style type="text/css">
              /*<![CDATA[*/
                table.doctrine-profiler {
                    background: none repeat scroll 0 0 #FFFFCC;
                    border-width: 1px;
                    border-style: outset;
                    border-color: #BF0000;
                    border-collapse: collapse;
                    font-size: 11px;
                    color: #222;
                 }
                table.doctrine-profiler th {
                    border:1px inset #BF0000;
                    padding: 3px;
                    padding-bottom: 3px;
                    font-weight: bold;
                    background: #E03937;
                }
                table.doctrine-profiler td {
                    border:1px inset grey;
                    padding: 2px;
                }
                table.doctrine-profiler tr:hover {
                    background: #ffff88;
                }
                fieldset.doctrine-profiler legend {
                    background:#fff;
                    border:1px solid #333;
                    font-weight:700;
                    padding:2px 15px;
                }
                /*]]>*/
                </style>';
        
        echo '<p>&nbsp;</p><fieldset class="doctrine-profiler"><legend>Debug Console for Doctrine Queries</legend>';
        echo '<table class="doctrine-profiler" width="95%">';        
        echo '<tr>
                <th>Query Counter</th>
                <th>Command</th>
                <th>Time</th>
                <th>Query with placeholder (?) for parameters</th>
                <th>Parameters</th>
              </tr>';
              
        foreach ( $this->getProfiler() as $event )
        {
            /**
             * By activiating the following lines, only the "execute" queries are shown.
             * It's usefull for debugging a certain type of database statement.
             */
            /*
            if ($event->getName() != 'execute')
            {
                continue;
            }
            */

            # increase query counter
            $query_counter++;

            # increase time
            $time += $event->getElapsedSecs();

            echo '<tr>';
                echo '<td style="text-align: center;">' . $query_counter . '</td>';
                echo '<td style="text-align: center;">' . $event->getName() . '</td>';
                echo '<td>' . sprintf ( "%f" , $event->getElapsedSecs() ) . '</td>';
                echo '<td>' . $event->getQuery() . '</td>';
                $params = $event->getParams();
                if ( !empty($params))
                {
                      echo '<td>';
                      echo wordwrap(join(', ', $params),150,"\n",true);
                      echo '</td>';
                }
                else
                {
                      echo '<td>';
                      echo '&nbsp;';
                      echo '</td>';
                }
            echo '</tr>';            
        }
        echo '</table>';
        echo '<p style="font-weight: bold;">&nbsp; &raquo; &nbsp; '.$query_counter.' statements in ' . sprintf("%2.5f", $time) . ' secs.</p>';
        echo '</fieldset>';
    }

    /**
     * shutdown function for register_shutdown_function
     */
    public function shutdown()
    {
        if (defined('SHUTDOWN_FUNCTION_SUPPRESSION') and SHUTDOWN_FUNCTION_SUPPRESSION == false)
        {
            # append Doctrine's SQL-Profiling Report
            $this->displayProfilingHTML();
        }

        # save session before exit
        session_write_close();
    }
}
?>