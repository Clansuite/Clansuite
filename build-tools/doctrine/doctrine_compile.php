<?php
/**
 * Compile phpDoctrine
 * -------------------
 * 
 * This assembles all library files of phpDoctrine
 * to only ONE big library file, which can be included by:
 *
 * Usage : require_once 'path_to_doctrine/Doctrine.compiled.php';
 *
 * Whats going on?
 * ---------------
 *
 *  Xdebug Output:
 *  
 *  Doctrine::compile( )	        ..\doctrine_compile.php:7
 *  Doctrine_Compiler::compile( )	..\Doctrine.php:992
 */
require 'libraries/doctrine/Doctrine.php';

spl_autoload_register(array('Doctrine', 'autoload'));
#Doctrine_Manager::connection('mysql://clansuite:toop@localhost/clansuite');

Doctrine::compile();
?>