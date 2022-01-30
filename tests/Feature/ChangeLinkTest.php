<?php

declare(strict_types=1);

namespace Test\Feature;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use DI\Container;

class ChangeLinkTest extends FeatureTestCase
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
            ->method('get')
            ->with('abc3')
            ->willReturn(new Link(
                'abc3',
                'https://yandex.ru',
                null,
                []
            ))
        ;

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with(new Link(
                'abc3',
                'https://google.com',
                'Cool link to google',
                [
                    'homepage',
                    'mylink'
                ]
            ))
        ;

        $body = [
            'long_url' =>'https://google.com',
            'title' => 'Cool link to google',
            'tags' =>  ['homepage', 'mylink'],
        ];

        $response = $this->patch('/links/abc3', json_encode($body, JSON_THROW_ON_ERROR));

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testLinkNotFound(): void
    {
        $this->repository
            ->method('get')
            ->with('abc4')
            ->willReturn(null)
        ;

        $body = [
            'long_url' =>'https://google.com',
            'title' => 'Cool link to google',
            'tags' =>  ['homepage', 'mylink'],
        ];

        $response = $this->patch('/links/abc4', json_encode($body, JSON_THROW_ON_ERROR));

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testValidationError(): void
    {
        $this->repository
            ->method('get')
            ->with('abc3')
            ->willReturn(new Link(
                'abc3',
                'https://yandex.ru',
                null,
                []
            ))
        ;


        $body = [
            'long_url' =>'',
        ];

        $response = $this->patch('/links/abc3', json_encode($body, JSON_THROW_ON_ERROR));

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'long_url' => [
                        'Value cannot be empty',
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }
}
