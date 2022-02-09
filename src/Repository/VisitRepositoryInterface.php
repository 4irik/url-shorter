<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Visit;

interface VisitRepositoryInterface
{
    public function add(Visit $visit): void;
}
