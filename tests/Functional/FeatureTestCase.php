<?php

declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Slim\App;

class FeatureTestCase extends TestCase
{
    use FeatureRequestTrait;
    use FeatureJsonRequestTrait;

    private ?App $app;

    protected function setUp(): void
    {
        $this->app = require __DIR__ . '/../../src/bootstrap.php';
    }

    protected function tearDown(): void
    {
        $this->app = null;
    }

    public function getApp(): App
    {
        return $this->app;
    }
}
