<?php

declare(strict_types=1);

namespace App\Entity;

class Visit
{
    public function __construct(
        public readonly string $linkId,
        public readonly string $ip,
        public readonly string $userAgent,
        public readonly \DateTimeImmutable $dateTime,
    ) {
    }
}
