<?php

declare(strict_types=1);

namespace Test\Unit\Middleware;

use App\Middleware\LinkValidator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\NonBufferedBody;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;

class LinkValidatorTest extends TestCase
{
    public function testDataHaveError(): void
    {
        $request = (new Request(
            'POST',
            new Uri('https', 'localhost'),
            new Headers([]),
            [],
            [],
            new NonBufferedBody(),
            []
        ))->withParsedBody([
            'a' => 123,
        ]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->never())
            ->method('handle')
        ;

        $middleware = new LinkValidator([
            'properties' => [
                'a' => [
                    'type' => 'string',
                ],
            ],
            'additionalProperties' => false,
        ]);

        $response = $middleware($request, $handler);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertJsonStringEqualsJsonString(json_encode(['Value 123 is not a(n) "string"'], JSON_THROW_ON_ERROR), $response->getBody()->__toString());
    }

    public function testDataDontHaveErrors(): void
    {
        $request = (new Request(
            'POST',
            new Uri('https', 'localhost'),
            new Headers([]),
            [],
            [],
            new NonBufferedBody(),
            []
        ))->withParsedBody([
            'a' => 'avc',
        ]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response = new Response())
        ;

        $middleware = new LinkValidator([
            'type' => 'object',
            'properties' => [
                'a' => [
                    'type' => 'string',
                ],
            ],
            'additionalProperties' => false,
        ]);


        $this->assertSame($response, $middleware($request, $handler));
    }
}
