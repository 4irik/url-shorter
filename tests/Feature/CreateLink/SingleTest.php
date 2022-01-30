<?php

declare(strict_types=1);

namespace Test\Feature\CreateLink;

use Test\Feature\FeatureTestCase;

class SingleTest extends FeatureTestCase
{
    public function testSuccess(): void
    {
        $body = [
            'long_url' =>'https://google.com',
            'title' => 'Cool link to google',
            'tags' =>  ['homepage', 'mylink'],
        ];

        $response = $this->post('/links', json_encode($body, JSON_THROW_ON_ERROR));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'id' => 'abc3',
                        'short_url' => 'https://url-shorter.ru/abc3',
                        'long_url' =>'https://google.com',
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
        $body = [
            'long_url' => '',
            'title' => 'Cool link to google',
        ];

        $response = $this->post('/links', json_encode($body, JSON_THROW_ON_ERROR));

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
