<?php

declare(strict_types=1);

namespace App\Service\RandomString;

interface GeneratorInterface
{
    public function getRandomString(): string;
}
