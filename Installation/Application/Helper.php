<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Installation\Application;

use \Clansuite\Installation\Application\Exception as Exception;
use \Clansuite\Installation\Application\Helper as Helper;

class Helper
{
    /**
     * Writes the Database-Settings into the clansuite.config.php
     *
     * @param $data_array
     * @return BOOLEAN true, if clansuite.config.php could be written to the INSTALLATION_ROOT
     */
    public static function write_config_settings($data_array)
    {
        // Read/Write Handler for config files
        include KOCH_FRAMEWORK . 'Config/Adapter/INI.php';

        // the base class is needed for \Koch\Config\Adpater\Ini
        if (false === class_exists('AbstractConfig', false)) {
            require KOCH_FRAMEWORK . 'Config/AbstractConfig.php';
        }

        // throw not needed setting out, before data_array gets written to file
        unset($data_array['step_forward']);
        unset($data_array['lang']);
        unset($data_array['database']['create_database']);

        // read skeleton settings, which are the minimum settings for initial startup
        // (these are not asked from user during installation)
        $installer_config = \Koch\Config\Adapter\INI::readConfig(INSTALLATION_ROOT . 'config.skeleton.ini');

        // array merge: overwrite the array to the left, with the array to the right, when keys identical
        $data_array = array_merge_recursive($data_array, $installer_config);

        // write Config File to the APPLICATION/configuration folder
        if (false === \Koch\Config\Adapter\INI::writeConfig(ROOT_APP . 'Configuration/clansuite.php', $data_array)) {
            // config not written
            return false;
        }

        // config written
        return true;
    }

    /**
     * Array Merge Recursive
     *
     * @param $arr1 array
     * @param $arr2 array
     * @return recusrive merged array
     */
    public static function array_merge_rec($arr1, $arr2)
    {
        foreach ($arr2 as $k => $v) {
            if (false === array_key_exists($k, $arr1)) {
                $arr1[$k] = $v;
            } else {
                if (is_array($v)) {
                    $arr1[$k] = self::array_merge_rec($arr1[$k], $arr2[$k]);
                }
            }
        }

        return $arr1;
    }

    /**
     * removeDirectory
     *
     * Removes a directory and all files recursively.
     *
     * @param string The file or folder to be deleted.
     */
    public static function removeDirectory($dir)
    {
        // get files
        $files = array_merge(glob($dir . '/*'), glob($dir . '/.*'));
        if (strpos($dir, 'installation') === false) {
            die('ERROR!' . var_dump($dir));
        }

        foreach ($files as $file) {
            // skip the index.php
            if (preg_match('#[\\|/]\.$#', $file) || preg_match('#[\\|/]\.\.$#', $file)) {
                continue;
            }

            // skip dirs
            if (is_dir($file)) {
                self::removeDirectory($file);
            } else {
                chmod($file, 0777);
                unlink($file);
                echo '.'; #[Deleting File] '.$file.'.</br>';
            }
        }

        // try to apply delete permissiosn
        if (chmod($dir, 0777) === false) {
            echo '[Deleting Directory] Setting the permission to delete the directory on directory ' . $dir . ' failed!<br/>';
        } else {
            // echo '[Deleting Directory] Successfully applied permission to delete the directory on directory '.$dir.'!<br/>';
        }

        // try to remove directory
        if (rmdir($dir) === false) {
            echo '[Deleting Directory] Removing of directory ' . $dir . ' failed! Please remove it manually.<br/>';
        } else {
            // rmdir sucessfull
            // echo '[Deleting Directory] Removing of directory '.$dir.'<br/>';
        }
    }

    /**
     * Calculates the installation progress in percentages
     * based on the total number of steps and the current step.
     *
     * @params int $current_step The number of the current install step.
     * @params int $total_steps The total number of install steps.
     * @return float progress-value
     */
    public static function calculateProgress($current_step, $total_steps)
    {
        if ($current_step <= 1) {
            return 0;
        }

        return round((100 / $total_steps) * $current_step, 0);
    }

    /**
     * Fetches Model Paths for all modules
     *
     * @return array Array with all model directories
     */
    public static function getModelPathsForAllModules()
    {
        $model_dirs = array();

        /**
         * All Module Entites
         */
        $dirs = glob(ROOT_APP . '/Modules/' . '[a-zA-Z]*', GLOB_ONLYDIR);

        foreach ($dirs as $key => $dir_path) {
            // Entity Path
            $entity_path = $dir_path . '/Model/Entity/';

            if (is_dir($entity_path)) {
                $model_dirs[] = $entity_path;
            }

            // Repository Path
            $repos_path = $dir_path . '/Model/Repository/';

            if (is_dir($repos_path)) {
                $model_dirs[] = $repos_path;
            }
        }

        /**
         * Core Entities
         */
        $model_dirs[] = ROOT_APP . 'Doctrine';

        // array_unique
        $model_dirs = array_keys(array_flip($model_dirs));

        #\Koch\Debug\Debug::printR($model_dirs);

        return $model_dirs;
    }

    public static function getDoctrineEntityManager($connectionParams = null)
    {
        try {
            if (is_array($connectionParams) === false) {
                include KOCH_FRAMEWORK . 'Config/Adapter/INI.php';

                // get clansuite config
                $clansuite_config = \Koch\Config\Adapter\INI::readConfig(ROOT_APP . 'Configuration/clansuite.php');

                // reduce config array to the dsn/connection settings
                $connectionParams = $clansuite_config['database'];
            }

            // setCharset() UTF8
            $connectionParams['charset'] = 'UTF8';

            // connect
            $config = new \Doctrine\DBAL\Configuration();
            $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

            // get Event and Config
            $event = $connection->getEventManager();
            $config = new \Doctrine\ORM\Configuration();

            // add Table Prefix
            $prefix = $connectionParams['prefix'];
            $tablePrefix = new \DoctrineExtensions\TablePrefix\TablePrefix($prefix);
            $event->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

            // setup Cache
            $cache = new \Doctrine\Common\Cache\ArrayCache();
            $config->setMetadataCacheImpl($cache);

            // setup Proxy Dir
            $config->setProxyDir(realpath(ROOT_APP . 'Doctrine'));
            $config->setProxyNamespace('proxies');

            // setup Annotation Driver
            $driverImpl = $config->newDefaultAnnotationDriver(
                Helper::getModelPathsForAllModules());
            $config->setMetadataDriverImpl($driverImpl);

            // finally: instantiate EntityManager
            $entityManager = \Doctrine\ORM\EntityManager::create($connection, $config, $event);

            return $entityManager;
        } catch (\Exception $e) {
            $msg = 'The initialization of Doctrine2 failed!' . NL . NL . 'Reason: ' . $e->getMessage();
            throw new Exception($msg);
        }
    }
}
