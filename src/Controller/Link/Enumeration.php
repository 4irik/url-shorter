<?php

declare(strict_types=1);

namespace App\Controller\Link;

use App\Repository\LinkRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Enumeration
{
    public function __construct(private readonly LinkRepositoryInterface $repository)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(json_encode($this->repository->list(), JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
