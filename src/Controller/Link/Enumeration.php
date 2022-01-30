<?php

declare(strict_types=1);

namespace App\Controller\Link;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Enumeration
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $links = [
            [
                'id' => 'fds3',
                'long_url' => 'https://google.com',
                'title' => 'Google',
                'tags' => [
                    'search_engines',
                    'google',
                ],
            ],
            [
                'id' => 'hj5g',
                'long_url' => 'https://yandex.ru',
                'title' => 'Yandex',
                'tags' => [
                    'search_engines',
                    'yandex',
                ],
            ],
        ];

        $response->getBody()->write(json_encode($links, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
