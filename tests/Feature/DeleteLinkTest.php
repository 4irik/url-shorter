<?php

declare(strict_types=1);

namespace Test\Feature;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use DI\Container;

class DeleteLinkTest extends FeatureTestCase
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
            ->willReturn($link = new Link(
                'abc3',
                'https://google.com',
                null,
                []
            ))
        ;
        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($link)
        ;

        $response = $this->delete('/links/abc3');

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testLinkNotFound(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with('abc3')
            ->willReturn(null)
        ;

        $response = $this->delete('/links/abc3');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
