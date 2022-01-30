<?php

declare(strict_types=1);

namespace App\Controller\Link;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Delete
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if ($args['id'] !== 'abc3') {
            return $response->withStatus(404);
        }
        return $response->withStatus(204);
    }
}
