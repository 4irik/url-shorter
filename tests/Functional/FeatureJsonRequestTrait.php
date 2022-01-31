<?php

declare(strict_types=1);

namespace Test\Functional;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Interfaces\HeadersInterface;

trait FeatureJsonRequestTrait
{
    public function jsonRequest(
        string $method,
        string $url,
        HeadersInterface $headers = null,
        array $cookie = [],
        array $serverParams = [],
        array $body = [],
    ): ResponseInterface {
        if ($headers === null) {
            $headers = new Headers();
        }

        $headers->addHeader('Content-Type', 'application/json');

        return $this->request(
            $method,
            $url,
            $headers,
            $cookie,
            $serverParams,
            json_encode($body, JSON_THROW_ON_ERROR)
        );
    }

    public function jsonGet(string $url, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->jsonRequest(
            'GET',
            $url,
            new Headers($headers),
            $cookie
        );
    }

    public function jsonPost(string $url, array $body, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->jsonRequest(
            'POST',
            $url,
            new Headers($headers),
            $cookie,
            [],
            $body
        );
    }

    public function jsonPatch(string $url, array $body, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->jsonRequest(
            'PATCH',
            $url,
            new Headers($headers),
            $cookie,
            [],
            $body
        );
    }

    public function jsonDelete(string $url, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->jsonRequest(
            'DELETE',
            $url,
            new Headers($headers),
            $cookie
        );
    }
}
