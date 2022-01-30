<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Redirect
{
    private LinkRepositoryInterface $repository;

    public function __construct(LinkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $id): ResponseInterface
    {
        $link = $this->repository->get($id);

        if ($link instanceof Link) {
            return $response
                ->withHeader('Location', $link->link)
                ->withStatus(302)
            ;
        }

        return $response->withStatus(404);
    }
}
