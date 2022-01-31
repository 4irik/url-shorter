<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Entity\Link;
use App\Entity\Visit;
use App\Repository\LinkRepositoryInterface;
use App\Repository\VisitRepositoryInterface;
use DI\Container;

class RedirectTest extends FeatureTestCase
{
    /**
     * @var LinkRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $linkRepository;
    /**
     * @var VisitRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $visitRepository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Container $container */
        $container = $this->getApp()->getContainer();

        $container->set(
            LinkRepositoryInterface::class,
            $this->linkRepository = $this->createMock(LinkRepositoryInterface::class)
        );

        $container->set(
            VisitRepositoryInterface::class,
            $this->visitRepository = $this->createMock(VisitRepositoryInterface::class)
        );
    }

    public function testSuccess(): void
    {
        $this->linkRepository
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
        $this->visitRepository
            ->expects($this->once())
            ->method('add')
            ->with(new Visit(
                'abc3',
                '127.0.0.1',
                'Fake/4.5 [en] (X11; U; Linux 2.2.9 i586)',
                new \DateTimeImmutable('1643599711')
            ))
        ;

        $response = $this->get('/abc3');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://google.com', $response->getHeaderLine('Location'));
        $this->assertEquals('', $response->getBody()->__toString());
    }

    public function testFail(): void
    {
        $this->linkRepository
            ->expects($this->once())
            ->method('get')
            ->with('abc3')
            ->willReturn(null)
        ;

        $this->visitRepository
            ->expects($this->never())
            ->method('add')
        ;

        $response = $this->get('/abc3');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
