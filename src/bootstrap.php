<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorProxyInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// работа со ссылками
$app->group('/links', static function (RouteCollectorProxyInterface $group): void {
    // создание одной или набора
    $group->post('', static function (Request $request, Response $response): Response {
        $isMltidimensional  = static function (array $array): bool {
            return isset($array[0]);
        };


        $data = json_decode(
            $request->getBody()->__toString(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        if (!$isMltidimensional($data)) {
            $data = [$data];
        }

        if ($data[0]['long_url'] === '') {
            $response->getBody()->write(
                json_encode(
                    [
                        [
                            'long_url' => ['Value cannot be empty'],
                        ]
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
            return $response->withStatus(422);
        }


        $data[0] = [
            ...[
                'id' => 'abc3',
                'short_url' => 'https://url-shorter.ru/abc3'
            ],
            ...$data[0]
        ];


        $response->getBody()->write(
            json_encode(
                $data,
                JSON_THROW_ON_ERROR
            )
        );

        return $response->withStatus(201);
    });

    // правка
    $group->patch('/{id}', static function (Request $request, Response $response, array $args): Response {
        if ($args['id'] !== 'abc3') {
            return $response->withStatus(404);
        }


        $data = json_decode(
            $request->getBody()->__toString(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        if ($data['long_url'] === '') {
            $response->getBody()->write(json_encode(
                [
                    'long_url' => [
                        'Value cannot be empty'
                    ],
                ],
                JSON_THROW_ON_ERROR
            ));

            return $response->withStatus(422);
        }


        return $response->withStatus(204);
    });

    // удаление
    $group->delete('/{id}', static function (Request $request, Response $response, array $args): Response {
        if ($args['id'] !== 'abc3') {
            return $response->withStatus(404);
        }
        return $response->withStatus(204);
    });

    // получаем запись
    $group->get('/{id}', static function (Request $request, Response $response, array $args): Response {
        if ($args['id'] !== 'abc3') {
            return $response->withStatus(404);
        }

        $link = [
            'id' => 'abc3',
            'long_url' => 'https://google.com',
            'title' => 'Some title',
            'tags' => [
                'search_engines',
                'google',
            ],
        ];

        $response->getBody()->write(json_encode($link, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // получаем все записи
    $group->get('', static function (Request $request, Response $response): Response {
        $links = [
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
        ];

        $response->getBody()->write(json_encode($links, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    });
});

$app->get('/{id}', function (Request $request, Response $response, array $args) {
    if ($args['id'] === 'abc3') {
        $response = $response->withHeader('Location', 'https://google.com');
        return $response->withStatus(302);
    }

    return $response->withStatus(404);
});

return $app;
