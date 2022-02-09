<?php

declare(strict_types=1);

namespace App\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use League\JsonGuard\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use GuzzleHttp\Psr7;

class LinkValidator
{
    private readonly object $schema;

    public function __construct(array $schema)
    {
        $this->schema = $this->arrayToObject($schema);
    }

    protected function arrayToObject(array $data): object
    {
        return json_decode(json_encode($data, JSON_THROW_ON_ERROR), false, flags:JSON_THROW_ON_ERROR);
    }


    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validator = new Validator(
            isset($data[0])
                ? array_map([$this, 'arrayToObject'], $data)
                : $this->arrayToObject($data),
            $this->schema
        );

        if ($validator->passes()) {
            return $handler->handle($request);
        }

        $errorMessages = [];
        foreach ($validator->errors() as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return new Response(
            StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
            (new Headers())->addHeader('Content-Type', 'application/json'),
            Psr7\Utils::streamFor(json_encode($errorMessages, JSON_THROW_ON_ERROR))
        );
    }
}
