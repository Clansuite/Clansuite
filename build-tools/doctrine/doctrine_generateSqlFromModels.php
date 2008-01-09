<?php

require_once('path-to-doctrine/lib/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

Doctrine_Manager::connection('mgsql://user:pass@localhost/test');

$queries = Doctrine::generateSqlFromModels('models');

echo $queries;

?>
