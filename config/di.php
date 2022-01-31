<?php

declare(strict_types=1);

use App\Service\RandomString\GeneratorInterface;
use App\Service\RandomString\RandomIntBaseGenerator;

return [
    GeneratorInterface::class => static function (): GeneratorInterface {
        return new RandomIntBaseGenerator(7);
    },
];
