<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in(__DIR__);

return (new PhpCsFixer\Config())->setFinder($finder);
