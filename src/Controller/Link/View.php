<?php

declare(strict_types=1);

namespace App\Controller\Link;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class View
{
    private LinkRepositoryInterface $repository;

    public function __construct(LinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $id): ResponseInterface
    {
        $link = $this->repository->get($id);

        if (!$link instanceof Link) {
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($link, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
