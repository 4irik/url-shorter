<?php

declare(strict_types=1);

namespace Test\Feature;

use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Headers;
use Slim\Psr7\Interfaces\HeadersInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

class FeatureTestCase extends TestCase
{
    private ?App $app;

    protected function setUp(): void
    {
        $this->app = require __DIR__ . '/../../src/bootstrap.php';
    }

    protected function tearDown(): void
    {
        $this->app = null;
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function request(
        string $method,
        string $url,
        HeadersInterface $headers = null,
        array $cookie = [],
        array $serverParams = [],
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
