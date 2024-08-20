<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Link;

interface LinkRepositoryInterface
{
    public function get(string $id): ?Link;

    public function delete(Link $link): void;

    /**
     * @return Link[]
     */
    public function list(): array;

    public function save(Link $link): void;
}
