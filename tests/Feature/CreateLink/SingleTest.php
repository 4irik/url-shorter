<?php

declare(strict_types=1);

namespace Test\Feature\CreateLink;

use App\Entity\Link;
use App\Repository\LinkRepositoryInterface;
use App\Service\RandomString;
use DI\Container;
use Test\Feature\FeatureTestCase;

class SingleTest extends FeatureTestCase
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
            'long_url' =>'https://google.com',
            'title' => 'Cool link to google',
            'tags' =>  ['homepage', 'mylink'],
        ];

        $this->generator
            ->expects($this->once())
            ->method('getRandomString')
            ->willReturn($id = 'abc3')
        ;

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with(new Link(
                $id,
                ...array_values($body)
            ))
        ;

        $response = $this->jsonPost('/links', $body);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'id' => $id,
                        'link' =>'https://google.com',
                        'title' => 'Cool link to google',
                        'tags' =>  ['homepage', 'mylink'],
                    ]
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
            'long_url' => '',
            'title' => 'Cool link to google',
        ];

        $response = $this->jsonPost('/links', $body);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'long_url' => [
                            'Value cannot be empty',
                        ],
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }
}
