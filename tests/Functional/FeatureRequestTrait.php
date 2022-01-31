<?php

declare(strict_types=1);

namespace Test\Functional;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Interfaces\HeadersInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

trait FeatureRequestTrait
{
    public function request(
        string $method,
        string $url,
        HeadersInterface $headers = null,
        array $cookie = [],
        array $serverParams = [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_USER_AGENT' => 'Fake/4.5 [en] (X11; U; Linux 2.2.9 i586)',
            'REQUEST_TIME' => 1643599711,
        ],
        string $body = '',
    ): ResponseInterface {
        $fp = fopen('php://temp', 'r+');
        fwrite($fp, $body);

        $serverRequest = new Request(
            $method,
            new Uri('http', 'localhost', 80, $url),
            $headers ?? new Headers([], []),
            $cookie,
            $serverParams,
            new Stream($fp)
        );


        return $this->app->handle($serverRequest);
    }

    public function get(string $url, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->request(
            'GET',
            $url,
            new Headers($headers),
            $cookie
        );
    }

    public function post(string $url, string $body, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->request(
            'POST',
            $url,
            new Headers($headers),
            $cookie,
            [],
            $body
        );
    }

    public function patch(string $url, string $body, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->request(
            'PATCH',
            $url,
            new Headers($headers),
            $cookie,
            [],
            $body
        );
    }

    public function delete(string $url, array $headers = [], array $cookie = []): ResponseInterface
    {
        return $this->request(
            'DELETE',
            $url,
            new Headers($headers),
            $cookie
        );
    }
}
