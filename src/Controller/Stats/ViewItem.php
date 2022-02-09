<?php

declare(strict_types=1);

namespace App\Controller\Stats;

use App\Repository\StatisticRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ViewItem
{
    public function __construct(private readonly StatisticRepositoryInterface $repository)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $id): ResponseInterface
    {
        $response->getBody()->write(json_encode($this->repository->getByIdByDays($id), JSON_THROW_ON_ERROR));

        return $response;
    }
}
