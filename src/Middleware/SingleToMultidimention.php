<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Преобразует одномерный массив в двумерный
 */
class SingleToMultidimention
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isMltidimensional  = static fn (array $array): bool => isset($array[0]);

        $data = $request->getParsedBody();

        if (!$isMltidimensional($data)) {
            $request = $request->withParsedBody([$data]);
        }

        return $handler->handle($request);
    }
}
