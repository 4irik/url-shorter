<?php

declare(strict_types=1);

namespace App\Entity;

class Link
{
    public function __construct(
        public readonly string $id,
        public readonly string $link,
        public readonly ?string $title,
        public readonly array $tags,
    ) {
    }
}
