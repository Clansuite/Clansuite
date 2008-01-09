<?php

require_once('path-to-doctrine/lib/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

//in order to export we need a database connection
Doctrine_Manager::connection('mysql://user:pass@localhost/test');

Doctrine::createTablesFromModels('models');

?>