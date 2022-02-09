<?php

declare(strict_types=1);

namespace App\Repository;

interface StatisticRepositoryInterface
{
    public function getByIdByDays(string $id): iterable;

    public function getAll(): iterable;
}
