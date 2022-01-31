<?php

declare(strict_types=1);

namespace App\Controller\Link;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Update
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

        $data = $request->getParsedBody();

        $this->repository->save(new Link(
            $link->id,
            ...array_values($data)
        ));

        return $response->withStatus(204);
    }
}
