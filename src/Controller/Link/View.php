<?php

declare(strict_types=1);

namespace App\Controller\Link;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class View
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $id): ResponseInterface
    {
        if ($id !== 'abc3') {
            return $response->withStatus(404);
        }

        $link = [
            'id' => 'abc3',
            'long_url' => 'https://google.com',
            'title' => 'Some title',
            'tags' => [
                'search_engines',
                'google',
            ],
        ];

        $response->getBody()->write(json_encode($link, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
