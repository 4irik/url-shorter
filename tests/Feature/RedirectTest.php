<?php

declare(strict_types=1);

namespace Test\Feature;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use DI\Container;

class RedirectTest extends FeatureTestCase
{
    /**
     * @var LinkRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Container $container */
        $container = $this->getApp()->getContainer();

        $container->set(
            LinkRepositoryInterface::class,
            $this->repository = $this->createMock(LinkRepositoryInterface::class)
        );
    }

    public function testSuccess(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with('abc3')
            ->willReturn(new Link(
                'abc3',
                'https://google.com',
                null,
                []
            ))
        ;

        $response = $this->get('/abc3');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://google.com', $response->getHeaderLine('Location'));
    }

    public function testFail(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with('abc3')
            ->willReturn(null)
        ;

        $response = $this->get('/abc3');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
