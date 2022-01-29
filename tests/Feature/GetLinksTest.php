<?php

declare(strict_types=1);

namespace Test\Feature;

class GetLinksTest extends FeatureTestCase
{
    public function testGetAll(): void
    {
        $response = $this->get('links');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    [
                        'id' => 'fds3',
                        'long_url' => 'https://google.com',
                        'title' => 'Google',
                        'tags' => [
                            'search_engines',
                            'google',
                        ],
                    ],
                    [
                        'id' => 'hj5g',
                        'long_url' => 'https://yandex.ru',
                        'title' => 'Yandex',
                        'tags' => [
                            'search_engines',
                            'yandex',
                        ],
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testGetConcreteLink(): void
    {
        $response = $this->get('links/abc3');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'id' => 'abc3',
                    'long_url' => 'https://google.com',
                    'title' => 'Some title',
                    'tags' => [
                        'search_engines',
                        'google',
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $response->getBody()->__toString()
        );
    }

    public function testLinkNotFound(): void
    {
        $response = $this->get('links/abc4');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
