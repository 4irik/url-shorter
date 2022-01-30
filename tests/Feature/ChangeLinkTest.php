<?php

declare(strict_types=1);

namespace Test\Feature;

class ChangeLinkTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
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
