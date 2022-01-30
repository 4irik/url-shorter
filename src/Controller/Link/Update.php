<?php

declare(strict_types=1);

namespace App\Controller\Link;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Update
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if ($args['id'] !== 'abc3') {
            return $response->withStatus(404);
        }


        $data = json_decode(
            $request->getBody()->__toString(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        if ($data['long_url'] === '') {
            $response->getBody()->write(json_encode(
                [
                    'long_url' => [
                        'Value cannot be empty'
                    ],
                ],
                JSON_THROW_ON_ERROR
            ));

            return $response->withStatus(422);
        }


        return $response->withStatus(204);
    }
}
