<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->path([
        'src',
        'public',
        'tests',
    ])
    ->in(__DIR__)
;


$config = new PhpCsFixer\Config();

return $config->setFinder($finder);
