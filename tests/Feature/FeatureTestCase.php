<?php

declare(strict_types=1);

namespace Test\Feature;

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
    protected static ?App $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$app = require_once __DIR__ . '/../../src/bootstrap.php';
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


        return self::$app->handle($serverRequest);
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
}
