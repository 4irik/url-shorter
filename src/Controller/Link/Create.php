<?php

declare(strict_types=1);

namespace App\Controller\Link;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Create
{
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


        $data[0] = [
            ...[
                'id' => 'abc3',
                'short_url' => 'https://url-shorter.ru/abc3'
            ],
            ...$data[0]
        ];


        $response->getBody()->write(
            json_encode(
                $data,
                JSON_THROW_ON_ERROR
            )
        );

        return $response->withStatus(201);
    }
}
