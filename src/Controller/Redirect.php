<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Redirect
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $id): ResponseInterface
    {
        if ($id === 'abc3') {
            $response = $response->withHeader('Location', 'https://google.com');
            return $response->withStatus(302);
        }

        return $response->withStatus(404);
    }
}
