<?php

declare(strict_types=1);

namespace Test\Feature;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use DI\Container;

class GetLinksTest extends FeatureTestCase
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

    public function testGetAll(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('list')
            ->willReturn([
                new Link(
                    'abc3',
                    'https://google.com',
                    'Google',
                    [
                        'search_engines',
                        'google',
                    ]
                ),
                new Link(
                    'hj5g',
                    'https://yandex.ru',
                    'Yandex',
                    [
                        'search_engines',
                        'yandex',
                    ]
                ),
            ])
        ;

        $response = $this->get('links');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'id' => 'abc3',
                        'link' => 'https://google.com',
                        'title' => 'Google',
                        'tags' => [
                            'search_engines',
                            'google',
                        ],
                    ],
                    [
                        'id' => 'hj5g',
                        'link' => 'https://yandex.ru',
                        'title' => 'Yandex',
                        'tags' => [
                            'search_engines',
                            'yandex',
                        ],
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testGetConcreteLink(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with('abc3')
            ->willReturn(new Link(
                'abc3',
                'https://google.com',
                'Some title',
                [
                    'search_engines',
                    'google',
                ]
            ))
        ;

        $response = $this->get('links/abc3');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'id' => 'abc3',
                    'link' => 'https://google.com',
                    'title' => 'Some title',
                    'tags' => [
                        'search_engines',
                        'google',
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testLinkNotFound(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with('abc4')
            ->willReturn(null)
        ;

        $response = $this->get('links/abc4');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
