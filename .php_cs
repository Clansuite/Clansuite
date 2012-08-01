<?php

/**
 * php-cs-fixer - configuration file
 */

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->ignoreVCS(true)
    ->notName('.php_cs')
    ->notName('php-cs-fixer.report.txt')
    ->notName('composer.*')
    ->notName('*.phar')
    ->notName('*.ico')
    ->notName('*.ttf')
    ->notName('*.gif')
    ->notName('*.swf')
    ->notName('*.jpg')
    ->notName('*.png')
    ->notName('*.exe')
    ->notName('*classmap.php')
    ->notName('Utf8FallbackFunctions.php') // bug in php-cs-fixer, adds "public" to global functions
    ->exclude('img')
    ->exclude('images')
    ->exclude('ckeditor')
    ->exclude('simpletest')
    ->exclude('vendor')
    ->exclude('libraries')
    ->exclude('Libraries')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;