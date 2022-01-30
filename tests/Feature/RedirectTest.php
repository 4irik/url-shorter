<?php

declare(strict_types=1);

namespace Test\Feature;

class RedirectTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
        $response = $this->get('/abc3');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('https://google.com', $response->getHeaderLine('Location'));
    }

    public function testFail(): void
    {
        $response = $this->get('/fdf4');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
