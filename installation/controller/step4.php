<?php

// Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * STEP 4 - Database Source Name Configuration
 *
 * Procedure Notice:
 * 1) check if database settings are valid
 * 2) create table
 * 3) connect to database
 * 4) validate database schema
 * 5) insert database schema
 * 6) write database settings to config file
 */
class Clansuite_Installation_Step4 extends Clansuite_Installation_Page
{
    public function getDefaultValues()
    {
        $values = array();

        $values['host']     = isset($_SESSION['config']['database']['host'])     ? $_SESSION['config']['database']['host']     : 'localhost';
        $values['driver']   = isset($_SESSION['config']['database']['driver'])   ? $_SESSION['config']['database']['driver']   : 'pdo_mysql';
        $values['dbname']   = isset($_SESSION['config']['database']['dbname'])   ? $_SESSION['config']['database']['dbname']   : 'clansuite';
        $values['user']     = isset($_SESSION['config']['database']['user'])     ? $_SESSION['config']['database']['user']     : '';
        $values['password'] = isset($_SESSION['config']['database']['password']) ? $_SESSION['config']['database']['password'] : '';
        $values['prefix']   = isset($_SESSION['config']['database']['prefix'])   ? $_SESSION['config']['database']['prefix']   : 'cs_';
        $values['create_database'] = isset($_SESSION['config']['database']['create_database']) ? $_SESSION['config']['database']['create_database'] : '0';

        return $values;
    }

    public function validateFormValues()
    {
        $error = '';

        /**
         *  Valid Database Settings.
         *
         * Ensure the database settings incomming from input-fields are valid.
         */
        if(!empty($_POST['config']['database']['dbname'])
            and !empty($_POST['config']['database']['host'])
            and !empty($_POST['config']['database']['driver'])
            and !empty($_POST['config']['database']['user'])
            and isset($_POST['config']['database']['password']))
        {
            /**
             * This appends the error message with more pieces of information,
             * in case the validity of the "database name" FAILED.
             *
             * The database name has serious restrictions:
             * Forbidden are database names containing
             * only numbers and names like mysql-database commands.
             */
            if(preg_match("![\"'=*{}/\\?:<>]+!i", $_POST['config']['database']['dbname']))
            {
                $error .= '<p>The database name you have entered ("' . $_POST['config']['database']['dbname'] . '") is invalid.</p>';
                $error .= '<p> It can only contain alphanumeric characters, periods or underscores.';
                $error .= ' You might only use chars printed within brackets: [A-Z], [a-z], [0-9], [-_].</p>';
                $error .= '<p> Forbidden are database names containing only numbers and names like mysql-database commands.</p>';
            }

            if(!ctype_alnum($_POST['config']['database']['user']))
            {
                $error .= '<p>The database username might only contain alphanumeric characters.';
            }

            if($error != '')
            {
               $this->setErrorMessage($error);
               return false;
            }

            // Values are valid!
            return true;
        }
        else
        {
            // Setup Error Message
            $this->setErrorMessage($this->language['ERROR_FILL_OUT_ALL_FIELDS']);

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        /**
         * 2) Create database.
         *
         * Has the user requested to create the database?
         */
        if(isset($_POST['config']['database']['create_database'])
                and $_POST['config']['database']['create_database'] == 'on')
        {
            try
            {
                # connection without dbname (must be blank for create table)
                $connectionParams = array(
                    'user' => $_POST['config']['database']['user'],
                    'password' => $_POST['config']['database']['password'],
                    'host' => $_POST['config']['database']['host'],
                    'driver' => $_POST['config']['database']['driver'],
                );

                $config = new \Doctrine\DBAL\Configuration();
                $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
                $connection->setCharset('UTF8');

                /**
                 * fetch doctrine schema manager
                 * and create database
                 */
                $schema_manager = $connection->getSchemaManager();
                $schema_manager->createDatabase($_POST['config']['database']['dbname']);

                /**
                 * Another way of doing this is via the specific database platform command.
                 * Then for creating the database the platform is asked, which SQL CMD to use.
                 * For "pdo_mysql" it would result in a string like 'CREATE DATABASE name'.
                 */
                #$db = $connection->getDatabasePlatform();
                #$sql = $db->getCreateDatabaseSQL('databasename');
                #$connection->exec($sql);

                # Drop Connection.
                unset($connection);
            }
            catch(Exception $e)
            {
                // force return
                $this->setStep(4);

                $error = $this->language['ERROR_WHILE_CREATING_DATABASE'] . NL . NL;
                $error .= $e->getMessage() . '.';

                $this->setErrorMessage($error);
            }
        }

        /**
         * 3) Connect to Database
         */
        # Setup Connection Parameters. This time with "dbname".
        $connectionParams = array(
            'dbname' => $_POST['config']['database']['dbname'],
            'user' => $_POST['config']['database']['user'],
            'password' => $_POST['config']['database']['password'],
            'host' => $_POST['config']['database']['host'],
            'driver' => $_POST['config']['database']['driver'],
            'prefix' => $_POST['config']['database']['prefix'],
        );

        $entityManager = Clansuite_Installation_Helper::getDoctrineEntityManager($connectionParams);

        /**
         * 4) Validate Database Schemas
         */
        try
        {
            # instantiate validator
            $validator = new \Doctrine\ORM\Tools\SchemaValidator($entityManager);

            # validate
            $validation_error = $validator->validateMapping();

            # handle validation errors
            #if($validation_error)
            #{
                # @todo this is experimental...
                var_dump($validation_error);
            #}
        }
        catch(Exception $e)
        {
            // force return
            $this->setStep(4);

            $error = $this->language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();

            $this->setErrorMessage($error);
        }

        /**
         * 5) Insert/Update Schemas
         *
         * "recreate" will do a database drop, before schemas are updated.
         */
        try
        {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
            if(isset($_GET['recreate']))
            {
                $schemaTool->dropSchema($metadata);
            }
            $schemaTool->updateSchema($metadata);

            $entityManager->flush();
        }
        catch(Exception $e)
        {
            $html = '';
            $html .= 'The update failed!' . NL;
            $html .= 'Do you want to force a database drop (' . $connectionParams['dbname'] . ')?' . NL;
            $html .= 'This will result in a total loss of all data and database tables.' . NL;
            $html .= 'It will allow for an clean installation of the database.' . NL;
            $html .= 'WARNING: Act carefully!' . NL;
            $html .= '<form action="index.php?step=4&recreate=true" method="post">';
            $html .= '<input type="submit" value="Recreate Database" class="retry"></form>';

            // force return
            $this->setStep(4);

            $error = $this->language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();
            $error .= NL . NL . $html;

            $this->setErrorMessage($error);
        }

        /**
         * 6. Write Settings to clansuite.config.php
         */
        if(false === Clansuite_Installation_Helper::write_config_settings($_POST['config']))
        {
            // force return
            $this->setStep(4);

            $error = 'Config not written.' . NL;

            $this->setErrorMessage($error);
        }
    }
}
?>