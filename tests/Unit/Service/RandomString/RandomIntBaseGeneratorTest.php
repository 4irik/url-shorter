<?php

declare(strict_types=1);

namespace Test\Unit\Service\RandomString;

use App\Service\RandomString\RandomIntBaseGenerator;
use PHPUnit\Framework\TestCase;

class RandomIntBaseGeneratorTest extends TestCase
{
    public function testInvalidLength(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Length must be a positive integer');

        new RandomIntBaseGenerator(-1);
    }

    public function testEmptyKeyspace(): void
    {
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Length of keyspace must be greater than 1');

        new RandomIntBaseGenerator(10, '');
    }

    public function testToShortKeyspace(): void
    {
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Length of keyspace must be greater than 1');

        new RandomIntBaseGenerator(10, 'a');
    }

    public function testUseKeyspace(): void
    {
        $this->assertEquals('aaaaa', (new RandomIntBaseGenerator(5, 'aa'))->getRandomString());
        $this->assertEquals('bbb', (new RandomIntBaseGenerator(3, 'bb'))->getRandomString());
    }
}
