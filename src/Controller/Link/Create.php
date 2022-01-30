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
        $isMltidimensional  = static function (array $array): bool {
            return isset($array[0]);
        };

        $data = json_decode(
            $request->getBody()->__toString(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        if (!$isMltidimensional($data)) {
            $data = [$data];
        }

        if ($data[0]['long_url'] === '') {
            $response->getBody()->write(
                json_encode(
                    [
                        [
                            'long_url' => ['Value cannot be empty'],
                        ]
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
            return $response->withStatus(422);
        }

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
