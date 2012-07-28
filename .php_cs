<?php

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('*.phar')
    ->exclude('vendor')
    ->exclude('libraries')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;