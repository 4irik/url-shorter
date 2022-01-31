<?php

declare(strict_types=1);

namespace App\Service\RandomString;

class RandomIntBaseGenerator implements GeneratorInterface
{
    private readonly int $max;

    public function __construct(
        private readonly int $length = 64,
        private readonly string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        if ($length < 1) {
            throw new \RangeException('Length must be a positive integer');
        }

        if (mb_strlen($this->keyspace) < 2) {
            throw new \LengthException('Length of keyspace must be greater than 1');
        }

        $this->max = mb_strlen($keyspace, '8bit') - 1;
    }

    public function getRandomString(): string
    {
        $pieces = [];
        for ($i = 0; $i < $this->length; ++$i) {
            $pieces []= $this->keyspace[random_int(0, $this->max)];
        }

        return implode('', $pieces);
    }
}
