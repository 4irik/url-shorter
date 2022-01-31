<?php

declare(strict_types=1);

namespace App\Controller\Link;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use App\Service\RandomString\GeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Create
{
    private LinkRepositoryInterface $repository;
    private GeneratorInterface $generator;

    public function __construct(LinkRepositoryInterface $repository, GeneratorInterface $generator)
    {
        $this->repository = $repository;
        $this->generator = $generator;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $links = [];
        foreach ($data as $linkData) {
            $this->repository->save($links[] = new Link(
                $this->generator->getRandomString(),
                ...array_values(
                    array_merge(
                        [
                            'long_url' => '',
                            'title' => null,
                            'tags' => [],
                        ],
                        $linkData
                    )
                )
            ));
        }

        $response->getBody()->write(
            json_encode(
                $links,
                JSON_THROW_ON_ERROR
            )
        );

        return $response->withStatus(201);
    }
}
