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

namespace Clansuite\Modules\Doctrine\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * DoctrineAdminController
 *
 * Doctrine Command Line Interface Commands
 *
 * build-all
 * build-all-load
 * build-all-reload
 * compile
 * create-db
 * create-tables
 * dql
 * drop-db
 * dump-data
 * generate-migration
 * generate-migrations-db
 * generate-migrations-models
 * generate-models-db
 * generate-models-yaml
 * generate-sql
 * generate-yaml-db
 * generate-yaml-models
 * load-data
 * migrate
 * rebuild-db
 *
 * @category    Clansuite
 * @package     Doctrine
 * @subpackage  Administration
 */
class DoctrineAdminController extends ModuleController
{
    /**
     * @var array Doctrine Paths to models, yaml, sql, fixtures, migration data
     */
    private static $options;

    /**
     * Module_Doctrine_Admin -> Execute
     */
    public function _initializeModule()
    {
        // Path to Doctrine Library
        Doctrine_Core::setPath( ROOT_LIBRARIES . 'doctrine/' );

        /**
         * Setup Paths depending on a specific module or the core
         */
        $bool = $this->request->issetParameter('modulename');
        if (isset($bool)) {
            self::$options = self::setupDoctrinePaths($this->request->getParameterFromGet('modulename'));
        } else {
            self::$options = self::setupDoctrinePaths();
        }

        self::$options += self::getDoctrineOptions();
    }

    public function actionList()
    {
        $this->display();
    }

    /**
     * Returns the doctrine paths array for models, yaml, sql, fixtures, migration data.
     *
     * You can select between paths for the core or for a module via parameter.
     * The core path is basically ROOT.'doctrine', while the module path is APPLICATION_MODULES_PATH.
     * $modulename followed by the specific data dir.
     *
     * This is also useable as configuration array for the Doctrine Command Line Interface (CLI).
     *
     * @param  string $modulename The modulename to build the paths for. Defaults to ROOT.doctrine
     * @return array  Doctrine Config Array with path definitions.
     */
    public static function setupDoctrinePaths($modulename = null)
    {
        // default path for models of the core
        $path = ROOT . 'doctrine' . DIRECTORY_SEPARATOR;

        // path to models of a module
        if (isset($modulename)) {
            $path = APPLICATION_MODULES_PATH . $modulename . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR;
        }

        // compose the paths accordingly
        $config = array(
                        'models_path'        => $path . 'models',
                        'yaml_schema_path'   => $path,
                        'sql_path'           => $path . 'sql',
                        'data_fixtures_path' => $path . 'fixtures',
                        'migrations_path'    => $path . 'migrations'
                       );

        // declare path constants
        define('DOCTRINE_MODELS_PATH',        $config['models_path']);
        define('DOCTRINE_YAML_SCHEMA_PATH',   $config['yaml_schema_path']);
        define('DOCTRINE_SQL_PATH',           $config['sql_path']);
        define('DOCTRINE_DATA_FIXTURES_PATH', $config['data_fixtures_path']);
        define('DOCTRINE_MIGRATIONS_PATH',    $config['migrations_path']);

        unset($path);

        return $config;
    }

    /**
     * Doctrine Configuration Array for the Generation of Models.
     *
     * This is also useable as configuration array for the Doctrine Command Line Interface (CLI).
     */
    public static function getDoctrineOptions()
    {
        // Setup Configuration Array for the Generation of Models
        $config['generate_models_options'] = array(
                        /**
                         *  PHP Documentor Comment Settings
                         */
                        'phpDocPackage'         => 'Doctrine',
                        'phpDocSubpackage'      => 'Records',
                        'phpDocName'            => 'Jens-Andre Koch',
                        'phpDocEmail'           => 'vain@clansuite.com',
                        /**
                         * Classname and Filename Settings
                         */
                        // generate BaseClasses too
                        'generateBaseClasses'   => true,
                        #'baseClassPrefix'       => 'Clansuite_ModelBase_',
                        'baseClassPrefix'       => 'Base_',
                       #'baseClassName'         => '',
                        'baseClassesDirectory'  => 'records',
                        'generateTableClasses'  => true,              // generate Tables and Records
                        'baseTableClassName'    => '',
                        'classPrefix'           => 'Clansuite_Model_', // determines the pear style naming convention for autoload
                        'classPrefixFiles'      => true,
                        'pearStyle'             => true,
                        'suffix'                => '.model.php'
                    );

        return $config;
    }

    /**
     * db2models
     *
     * This generates Models from Database.
     *
     * @see Doctrine_Core::generateModelsFromDb
     */
    public function actiondb2models()
    {
        Doctrine_Core::generateModelsFromDb( DOCTRINE_MODELS_PATH , array(), self::$options);

        $message  = '<b>The MODEL files have been generated from DATABASE.</b>';
        $message .= '<br /><br />Destination Folder: '. DOCTRINE_MODELS_PATH;
        self::setFlashmessage('success', $message);

        $this->redirectToReferer();
    }

    /**
     * db2yaml
     *
     * This generates YAML schema files from Database.
     */
    public function actiondb2yaml()
    {
        // load sfYAML (it's somehow not loaded by doctrine autoload)
        include ROOT_LIBRARIES .'doctrine/Doctrine/Parser/sfYaml/SfYaml.php';
        Doctrine_Core::generateYamlFromDb( DOCTRINE_YAML_SCHEMA_PATH . 'schema.yml', array(), self::$options);
        $message  = '<b>The YAML schema file has been successfully generated from DATABASE.</b>';
        $message .= '<br /><br />Destination File: ' . DOCTRINE_YAML_SCHEMA_PATH . 'schema.yml';
        $this->setFlashmessage('success', $message);
        $this->redirectToReferer();
    }

    /**
     * model2sql
     * This generates Sql From Models.
     *
     * @see Doctrine_Core::generateSqlFromModels()
     */
    public function actionmodels2sql()
    {
        $sql = Doctrine_Core::generateSqlFromModels();
        $sqlSchemaFile = DOCTRINE_SQL_PATH . DIRECTORY_SEPARATOR . DB_PREFIX . '_sqlschema_backup_'.date('dmY_His').'.sql';
        file_put_contents($sqlSchemaFile, $sql);
        $message  = 'Successfully written SQL for the current schema to disc.';
        $message .= 'Destination File: '.$sqlSchemaFile;
        unset($sql);
        unset($sqlSchemaFile);
        $this->setFlashmessage('success', $message);
        $this->redirectToReferer();
    }

    /**
     * model2yaml
     *
     * This generates YAML schema files from MODELS
     * This generates TABLES from MODELS.
     *
     * @see Doctrine_Core::generateSqlFromYaml()
     */
    public function actionmodels2yaml()
    {
        // load sfYAML (it's somehow not loaded by doctrine autoload)
        include ROOT_LIBRARIES .'doctrine/Doctrine/Parser/sfYaml/SfYaml.php';
        #Doctrine_Core::dropDatabases();
        #Doctrine_Core::createDatabases();
        Doctrine_Core::generateYamlFromModels( DOCTRINE_YAML_SCHEMA_PATH . DIRECTORY_SEPARATOR . 'schema.yml', DOCTRINE_MODELS_PATH );
        #Doctrine_Core::createTablesFromModels( DOCTRINE_MODELS_PATH );
        $message  = '<b>The YAML schema file has been successfully generated from MODELS.</b>';
        $message .= '<br /><br />Destination File: ' . DOCTRINE_YAML_SCHEMA_PATH . 'schema.yml';
        #$message .= '<br /><br />The DATABASE TABLES have been successfully generated from MODELS.';
        $this->setFlashmessage('success', $message);
        $this->redirectToReferer();
    }

    /**
     * yaml2models
     *
     * This generates MODELS from YAML schema files.
     * This generates TABLES from MODELS.
     *
     * @see Doctrine_Core::generateModelsFromYaml
     */
    public function actionyaml2models()
    {
        // load sfYAML (it's somehow not loaded by doctrine autoload)
        include ROOT_LIBRARIES .'doctrine/Doctrine/Parser/sfYaml/SfYaml.php';
        #Doctrine_Core::dropDatabases();
        #Doctrine_Core::createDatabases();
        Doctrine_Core::generateModelsFromYaml( DOCTRINE_YAML_SCHEMA_PATH . DIRECTORY_SEPARATOR . 'schema.yml', DOCTRINE_MODELS_PATH, self::$options );
        #Doctrine_Core::createTablesFromModels( DOCTRINE_MODELS_PATH );
        $message  = '<b>The Models have been successfully generated from the YAML schema file.</b>';
        $message .= '<br /><br />Destination Folder ' . DOCTRINE_MODELS_PATH;
        #$message .= '<br /><br />The DATABASE TABLES have been successfully generated from MODELS.';
        $this->setFlashmessage('success', $message);
        $this->redirectToReferer();
    }

    public static function register($panel, $group = 'Doctrine')
    {

        $panel->addCommand($group, Command::create(
                        'Create Schema', function ($container) {
                            $em = $container->entityManager;
                            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
                            $metadatas = $em->getMetadataFactory()->getAllMetadata();
                            $schemaTool->createSchema($metadatas);
                        }
                )
        );

        $panel->addCommand($group, Command::create(
                        'Update Schema', function ($container) {
                            $em = $container->entityManager;
                            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
                            $metadatas = $em->getMetadataFactory()->getAllMetadata();
                            $schemaTool->updateSchema($metadatas);
                        }
                )
        );

        $panel->addCommand($group, Command::create(
                        'Drop Schema', function ($container) {
                            $em = $container->entityManager;
                            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
                            $metadatas = $em->getMetadataFactory()->getAllMetadata();
                            $schemaTool->dropSchema($metadatas);
                        }
                )
        );
    }
}
