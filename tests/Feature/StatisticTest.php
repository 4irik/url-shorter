<?php

declare(strict_types=1);

namespace Test\Feature;

use App\Repository\StatisticRepositoryInterface;
use DI\Container;

class StatisticTest extends FeatureTestCase
{
    /**
     * @var StatisticRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Container $container */
        $container = $this->getApp()->getContainer();

        $container->set(StatisticRepositoryInterface::class, $this->repository = $this->createMock(StatisticRepositoryInterface::class));
    }


    public function testAll(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($all_statistic = [
                ['id' => 'abc1', 'total_views' => 100, 'unique_views' => 5],
                ['id' => 'abc2', 'total_views' => 1000, 'unique_views' => 4],
                ['id' => 'abc3', 'total_views' => 10000, 'unique_views' => 3],
                ['id' => 'abc4', 'total_views' => 100000, 'unique_views' => 2],
            ])
        ;

        $response = $this->jsonGet('/stats');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                $all_statistic,
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testByLink(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdByDays')
            ->with('abc3')
            ->willReturn($statistic = [
                ['total_views' => 100, 'unique_views' => 5, 'date' => '2022-01-01'],
                ['total_views' => 1000, 'unique_views' => 4, 'date' => '2022-02-01'],
                ['total_views' => 10000, 'unique_views' => 3, 'date' => '2022-03-01'],
                ['total_views' => 100000, 'unique_views' => 2, 'date' => '2022-04-01'],
            ])
        ;

        $response = $this->jsonGet('/stats/abc3');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                $statistic,
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testLinkNotFound(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdByDays')
            ->with('abc3')
            ->willReturn($statistic = [])
        ;

        $response = $this->jsonGet('/stats/abc3');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                $statistic,
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }
}
