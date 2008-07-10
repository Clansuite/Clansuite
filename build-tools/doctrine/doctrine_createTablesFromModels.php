<?php
require 'libraries/doctrine/Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://clansuite:toop@localhost/clansuite');

Doctrine::createTablesFromModels('/myrecords/generated');
?>