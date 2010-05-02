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
    *
    * @version    SVN: $Id$
    */

//Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Doctrine_Admin
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
class Clansuite_Module_Doctrine_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    protected $doctrine_cli;

    /**
     * Module_Doctrine_Admin -> Execute
     */
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # Setup the path constants
        # They are not configured via clansuite configuration, because access is only needed in here
        define('MODELS_PATH',        ROOT . DS . 'models');
        define('YAML_SCHEMA_PATH',   ROOT . DS . 'doctrine' . DS . 'schema');
        define('SQL_PATH',           ROOT . DS . 'doctrine' . DS . 'sql');
        define('DATA_FIXTURES_PATH', ROOT . DS . 'doctrine' . DS . 'fixtures');
        define('MIGRATIONS_PATH',    ROOT . DS . 'doctrine' . DS . 'migrations');

        # Setup Configuration Array for the Doctrine Command Line Interface (CLI)
        $config = array(
                        'models_path'         =>  MODELS_PATH,
                        'yaml_schema_path'    =>  YAML_SCHEMA_PATH,
                        'sql_path'            =>  SQL_PATH,
                        'data_fixtures_path'  =>  DATA_FIXTURES_PATH,
                        'migrations_path'     =>  MIGRATIONS_PATH
                       );

        # Setup Configuration Array for the Generation of Models
        $config['generate_models_options'] = array(
                        # PHP Documentor Comment Settings
                        'phpDocPackage'         => 'Doctrine',
                        'phpDocSubpackage'      => 'Records',
                        'phpDocName'            => 'Jens-André Koch',
                        'phpDocEmail'           => 'vain@clansuite.com',
                        # Classnames and Filenames
                        'generateBaseClasses'   => true,                        # generate BaseClasses too
                        #'baseClassPrefix'       => 'Clansuite_ModelsBase_',
                        'baseClassPrefix'       => 'Base_',
                       #'baseClassName'         => '',
                        'baseClassesDirectory'  => 'records',
                        'generateTableClasses'  => true,                        # generate Tables and Records
                        'baseTableClassName'    => '',
                        'classPrefix'           => 'Modules_Models_',           # determines the pear style nameing convention for autoload
                        'classPrefixFiles'      => true,
                        'pearStyle'             => true,
                        'suffix'                => '.model.php'
                );

        # Apply settings to the Doctrine_CLI object on instantiation and set it to class for later usage
        $this->doctrine_cli = new Doctrine_Cli($config);
    }

    public function action_admin_show()
    {
        $this->prepareOutput();
    }
}
?>