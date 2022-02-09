<?php

declare(strict_types=1);

namespace Test\Unit\Middleware;

use App\Middleware\SingleToMultidimention;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\NonBufferedBody;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

class SingleToMultidimentionTest extends TestCase
{
    public function testSingleToMultidimention(): void
    {
        $request = new Request(
            'POST',
            new Uri('https', 'localhost'),
            new Headers([]),
            [],
            [],
            new NonBufferedBody(),
            []
        );

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($request->withParsedBody([['a' => 1]]))
        ;

        $middleware = new SingleToMultidimention();
        $middleware($request->withParsedBody(['a' => 1]), $handler);
    }

    public function testMultidimentionToMultidimention(): void
    {
        $request = new Request(
            'POST',
            new Uri('https', 'localhost'),
            new Headers([]),
            [],
            [],
            new NonBufferedBody(),
            []
        );

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($request->withParsedBody([['a' => 1]]))
        ;

        $middleware = new SingleToMultidimention();
        $middleware($request->withParsedBody([['a' => 1]]), $handler);
    }
}
