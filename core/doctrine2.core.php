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
 * Clansuite Core Class for the Initialization of Doctrine 2
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Doctrine
 */
class Clansuite_Doctrine2
{
    /**
     * A DBAL Logger Object
     * 
     * @var object \Doctrine\DBAL\Logging\DebugStack
     */
    private static $_sqlLoggerStack = '';
    
    private static $execTime = '';
    
    /**
     * Initialize auto loader of Doctrine
     *
     * @return Doctrine_Enitity_Manager
     */
    public static function init($db_config)
    {
        # ensure doctrine2 exists in the libraries folder
        if(is_file(ROOT_LIBRARIES . 'Doctrine/Common/ClassLoader.php') === false)
        {
            throw new Clansuite_Exception('Doctrine2 not found. Check Libraries Folder.', 100);
        }

        # get isolated loader
        require_once(ROOT_LIBRARIES . 'Doctrine/Common/ClassLoader.php');

        # setup autoloaders with namespace and path to search in
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', realpath(ROOT_LIBRARIES));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(ROOT_LIBRARIES .  'Doctrine/Symfony'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Entities', realpath(ROOT . 'doctrine'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Repositories', realpath(ROOT . 'doctrine'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(ROOT . 'doctrine'));
        $classLoader->register();
        
        # Including Doctrine Extensions
        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', realpath(ROOT_LIBRARIES));
        $classLoader->register();
        $classLoader = new Doctrine\Common\ClassLoader("DoctrineExtensions\\NestedSet", realpath(ROOT_LIBRARIES . 'DoctrineExtensions'));
        $classLoader->register();

        # fetch doctrine config handler
        $config = new \Doctrine\ORM\Configuration();

        # cache: APC in production and Array in development mode
        if (extension_loaded('apc') and DEBUG == false)
        {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }
        else
        {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        }
        
        # set cache driver
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        # set annotation driver for entities
        $config->setMetadataDriverImpl(
            $config->newDefaultAnnotationDriver(
                self::getModelPathsForAllModules()));

        # @todo workaround till i find a better way to acquire all the models
        $config->getMetadataDriverImpl()->getAllClassNames();
        #$classes_loaded = $config->getMetadataDriverImpl()->getAllClassNames();
        #Clansuite_Debug::firebug($classes_loaded);

        # set proxy dirs
        $config->setProxyDir(realpath(ROOT . 'doctrine'));
        $config->setProxyNamespace('Proxies');

        # regenerate proxies only in debug and not in production mode
        if(DEBUG == true)
        {
            $config->setAutoGenerateProxyClasses(true);
        }
        else
        {
            $config->setAutoGenerateProxyClasses(false);
        }

        $connectionOptions = array(
            'driver'    => $db_config['database']['driver'],
            'user'      => $db_config['database']['username'],
            'password'  => $db_config['database']['password'],
            'dbname'    => $db_config['database']['name'],
            'host'      => $db_config['database']['host'],
            'charset'   => $db_config['database']['charset'],
            'driverOptions' => array(
                'charset' => $db_config['database']['charset']
            )
        );

        # Database Prefix
        # @todo doctrine2: the prefix is only applicable by eventhandling?
        define('DB_PREFIX', $db_config['database']['prefix'] );

        # set up Logger
        #$config->setSqlLogger(new \Doctrine\DBAL\Logging\EchoSqlLogger);

        # get EventManager
        $evm = new Doctrine\Common\EventManager;

        # Extension: Tree
        #$evm->addEventSubscriber(new Gedmo\Tree\TreeListener);

        # Extension: TablePrefix
        #$tablePrefix = new \DoctrineExtensions\TablePrefix(DB_PREFIX);
        #$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        # we need some more functions for mysql
        $config->addCustomNumericFunction('RAND', 'DoctrineExtensions\Query\Mysql\Rand');

        # set UTF-8 handling of database data
        $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
        $em->getConnection()->setCharset($db_config['database']['charset']);
        
        # set DBAL DebugStack Logger (also needed for counting queries)
        if(DEBUG == 1)
        {
            self::$_sqlLoggerStack = new \Doctrine\DBAL\Logging\DebugStack();        
            $em->getConfiguration()->setSQLLogger(self::$_sqlLoggerStack);
        }
        
        # echos SQL Queries directly on page
        #$em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        
        # done with config, remove to safe memory
        unset($db_config);

        return $em;
    }

    /**
     * Development / Helper Method for Schema Validation
     */
    public static function validateSchema()
    {
        $em = Clansuite_CMS::getEntityManager();
        $validator = new \Doctrine\ORM\Tools\SchemaValidator($em);
        $errors = $validator->validateMapping();
        Clansuite_Debug::printR($errors);
    }

    public static function debugLoadedClasses()
    {
        $em = Clansuite_CMS::getEntityManager();
        $config = $em->getConfiguration();
        #$config->addEntityNamespace('Core', $module_models_path); # = Core:Session
        #$config->addEntityNamespace('Module', $module_models_path); # = Module:News
        $classes_loaded = $config->getMetadataDriverImpl()->getAllClassNames();
        Clansuite_Debug::printR($classes_loaded);
    }

    public static function getModelPathsForAllModules()
    {
        $model_dirs = array();

        $dirs = Clansuite_ModuleInfoController::getModuleDirectories();

        foreach($dirs as $key => $dir_path)
        {
            /**
             * It's easier to include dirpath models (subfolder and files will be autoloaded)
             * therefor the records have to be removed
             */

            # Entity Path
            $entity_path = $dir_path . DS . 'model' . DS . 'entities' . DS;

            if(is_dir($entity_path))
            {
                $model_dirs[] = $entity_path;
            }

            # Repository Path
            $repos_path = $dir_path . DS . 'model' . DS . 'repositories' . DS;

            if(is_dir($repos_path))
            {
                $model_dirs[] = $repos_path;
            }
         }

        $model_dirs[] = ROOT . 'doctrine';

        $model_dirs = array_unique($model_dirs);

        #Clansuite_Debug::printR($model_dirs);
        return $model_dirs;
    }
    
    /**
     * Returns Query Counter and the exec time
     */
    public static function getStats()
    {
        echo sprintf('Doctrine Queries (%d @ %s sec)', 
                self::$_sqlLoggerStack->currentQuery, 
                round(self::getExecTime(), 3));
    }
    
    /**
     * Returns the Number of Queries 
     * 
     * @return int Number of Queries
     */
    public static function getNumberOfQueries()
    {
        return self::$_sqlLoggerStack->currentQuery;        
    }
    
    /**
     * Returns the total exec time for queries
     * 
     * @return string Number formatted time string.
     */
    public static function getExecTime()
    {
        $execTime = '';

        foreach(self::$_sqlLoggerStack->queries as $query)
        {
            $execTime += $query['executionMS'];
        }
        
        $execTime = number_format($execTime, 5);
        
        return $execTime;
    }
    
    /**
     * var_dumps the logger stack
     * for a simple overview of all queries
     */
    public static function getLoggerStack()
    {
        # @todo debug dump to firebug?
        var_dump(self::$_sqlLoggerStack);
    }
}
?>