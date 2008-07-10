<?php
require 'libraries/doctrine/Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://clansuite:toop@localhost/clansuite');

// import method takes one parameter: the import directory (the directory where
// the generated record files will be put in)
Doctrine::generateModelsFromDb('myrecords');
?>