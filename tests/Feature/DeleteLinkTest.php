<?php

declare(strict_types=1);

namespace Test\Feature;

class DeleteLinkTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
        $response = $this->delete('/links/abc3');

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testLinkNotFound(): void
    {
        $response = $this->delete('/links/abc4');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
