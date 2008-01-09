<?php
require 'libraries/doctrine/Doctrine.php';

spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://clansuite:toop@localhost/clansuite');

// import method takes one parameter: the import directory (the directory where
// the generated record files will be put in
Doctrine::generateModelsFromDb('myrecords');

/** 
Xdebug Dump

Doctrine::generateModelsFromDb( )	..\doctrine_export2sql.php:10
3	9.9808	1117456	Doctrine_Import->importSchema( )	..\Doctrine.php:664
4	10.4480	1313624	Doctrine_Import_Mysql->listTables( )	..\Import.php:215
5	10.4480	1313680	Doctrine_Connection->fetchColumn( )	..\Mysql.php:195
6	10.4480	1313896	Doctrine_Connection->execute( )	..\Connection.php:755
7	10.4480	1315000	Doctrine_Connection->connect( )	..\Connection.php:904
8	11.3717	1366000	PDO->__construct( )
*/
?>