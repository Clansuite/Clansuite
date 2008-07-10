<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
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
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
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
 */

class Clansuite_Doctrine
{
    #public $db = null; # holds a db instance

    function __construct(Clansuite_Config $config)
    {
        $this->config = $config; # set config instance
        
        # Load DBAL
        #$db = $this->injector->instantiate('clansuite_doctrine');
        $this->doctrine_initialize();
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
        #var_dump(class_exists("Doctrine"));
        // Require compiled or normal Library
        if (is_file( ROOT_LIBRARIES . 'doctrine/Doctrine.compiled.php'))
        {
            require ROOT_LIBRARIES .'doctrine/Doctrine.compiled.php';
        }
        else
        {
            require ROOT_LIBRARIES .'doctrine/Doctrine.php';
        }
        
        // Register Doctrine autoloader
        spl_autoload_register(array('Doctrine', 'autoload'));
       
        // Debug Modus
        if ( defined('DEBUG') && DEBUG===1 )
        {
            Doctrine::debug(true);
        }
        
        // Db Connection
        $this->prepareDbConnection();
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
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
        Doctrine::loadModels( ROOT . '/myrecords/' ); // This call will not require the found .php files
       
        // construct the Data Source Name (DSN)
        // Example: 
        #$dsn = 'mysql://clansuite:toop@localhost/clansuite';
        $dsn  = $this->config['database']['db_type'] . '://';
        $dsn .= $this->config['database']['db_username'] .  ':';
        $dsn .= $this->config['database']['db_password'] . '@';
        $dsn .= $this->config['database']['db_host'] . '/';
        $dsn .= $this->config['database']['db_name'];
        #echo 'Doctrine DSN: '.$dsn;

        // initalize a new Doctrine_Connection
        $manager = Doctrine_Manager::connection($dsn);

        // !! no actual database connection yet !!
        # object(Doctrine_Connection_Mysql) -> 'isConnected' => false
        # var_dump($manager);

        /**
         * Setup phpDoctrine Attributes for that later Connection
         */
         
        # Changing the database naming convention by adding DB_PREFIX
        $manager->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, DB_PREFIX ."%s"); 

        /**
         * 
         * Aggressive - It finds all .php files in a given path recursively and
           performs a require_once() on each file. Your files can be in subfolders, and
           the files can include multiple models. It is very flexible but the downside
           is that it will require_once() all files.

           Conservative - It finds all .php files in a given path recursively and
           builds an array of className => /path/to/file. The className is parsed from
           the name of the file, so each file must contain only one class and the file
           must be named after the class inside of it. This array is then referenced in
           Doctrine::autoload() and used to load models when they are asked for.
           
           Johnatan Wage on http://groups.google.com/group/doctrine-user
         */
        
        #$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);

        # Load Models (automatic + lazy loading)
        #Doctrine::loadModels( ROOT . '/myrecords/', Doctrine::MODEL_LOADING_CONSERVATIVE);  
        
        # Debug Listing of all loaded Doctrine Models
        #$models = Doctrine::getLoadedModels();
        #print_r($models);     

        // Validate All
        #$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);

        // Set portability for all rdbms = default
        #$manager->setAttribute('portability', Doctrine::PORTABILITY_ALL);

        // identifier quoting
        // disabled for now, because we have no reserved words as a field names
        #$manager->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);

        // Set Cache Driver
        /**
         * Doctrine_Cache_Exception: The apc extension must be loaded for using this backend !
        $cacheDriver = new Doctrine_Cache_Apc();
        $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
        // set the lifespan as one hour (60 seconds * 60 minutes = 1 hour = 3600 secs)
        $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN, 3600);
        */
        # set character set
        $manager->execute("SET CHARACTER SET utf8");
    }
}
?>