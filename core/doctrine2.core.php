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
     * Initialize auto loader of Doctrine
     *
     * @return Doctrine_Enitity_Manager
     */
    public static function init($db_config)
    {
        # get isolated loader
        require_once(ROOT_LIBRARIES . 'Doctrine/Common/ClassLoader.php');

        # setup autoloader with namespace doctrine and path to search in
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', realpath(ROOT_LIBRARIES));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(ROOT_LIBRARIES .  'Doctrine/Symfony'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Entities');
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(ROOT . 'doctrine'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', realpath(ROOT_LIBRARIES));
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
                    array(ROOT . 'doctrine/entities')));

        # set proxy dirs
        $config->setProxyDir(realpath(ROOT . 'doctrine'));
        $config->setProxyNamespace('Proxies');

        # # regenerate proxies only in debug and not in production mode
        if(DEBUG == true)
        {
            $config->setAutoGenerateProxyClasses(true);
        }
        else
        {
            $config->setAutoGenerateProxyClasses(false);
        }

        $connectionOptions = array(
            'driver'    => $db_config['database']['type'], #pdo_mysql
            'user'      => $db_config['database']['username'],
            'password'  => $db_config['database']['password'],
            'dbname'    => $db_config['database']['name'],
            'host'      => $db_config['database']['host']
        );

        # Database Prefix
        # @todo doctrine2: the prefix is only applicable by eventhandling?
        define('DB_PREFIX', $db_config['database']['prefix'] );

        # done with config, remove to safe mem
        unset($db_config);

        # get EventManager
        #$evm = new \Doctrine\Common\EventManager;

        # Add Extension for the Table Prefix as Eventlistener
        #$tablePrefix = new \DoctrineExtensions\TablePrefix(DB_PREFIX);
        #$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        return \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
    }
}
?>