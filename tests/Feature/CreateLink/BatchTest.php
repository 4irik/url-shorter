<?php

declare(strict_types=1);

namespace Test\Feature\CreateLink;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use DI\Container;
use Test\Feature\FeatureTestCase;
use App\Service\RandomString;

class BatchTest extends FeatureTestCase
{
    /**
     * @var LinkRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $repository;

    /**
     * @var RandomString\GeneratorInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $generator;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Container $container */
        $container = $this->getApp()->getContainer();

        $container->set(
            LinkRepositoryInterface::class,
            $this->repository = $this->createMock(LinkRepositoryInterface::class)
        );

        $container->set(
            RandomString\GeneratorInterface::class,
            $this->generator = $this->createMock(RandomString\GeneratorInterface::class)
        );
    }

    public function testSuccess(): void
    {
        $body = [
            [
                'long_url' =>'https://google.com',
                'title' => 'Cool link to google',
                'tags' =>  ['homepage', 'mylink'],
            ],
            [
                'long_url' =>'https://yandex.ru',
                'title' => 'Cool link to yandex',
            ],
        ];

        $this->generator
            ->expects($this->exactly(2))
            ->method('getRandomString')
            ->willReturnOnConsecutiveCalls('abc3', 'abc4')
        ;

        $this->repository
            ->expects($this->exactly(2))
            ->method('save')
            ->withConsecutive(
                [new Link('abc3', ...array_values($body[0]))],
                [new Link('abc4', ...array_values([...$body[1], ...['tags' => []]]))],
            )
        ;

        $response = $this->jsonPost('/links', $body);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'id' => 'abc3',
                        'link' =>'https://google.com',
                        'title' => 'Cool link to google',
                        'tags' =>  ['homepage', 'mylink'],
                    ],
                    [
                        'id' => 'abc4',
                        'link' =>'https://yandex.ru',
                        'title' => 'Cool link to yandex',
                        'tags' => [],
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testValidationError(): void
    {
        $this->generator
            ->expects($this->never())
            ->method('getRandomString')
        ;

        $this->repository
            ->expects($this->never())
            ->method('save')
        ;

        $body = [
            [
                'long_url' =>'',
                'title' => 'Cool link to yandex',
            ],
            [
                'long_url' =>'https://google.com',
                'title' => 'Cool link to google',
                'tags' =>  ['homepage', 'mylink'],
            ],
        ];

        $response = $this->jsonPost('/links', $body);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'long_url' => ['Value cannot be empty'],
                    ]
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }
}
