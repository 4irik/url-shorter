<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorProxyInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// работа со ссылками
$app->group('/links', static function (RouteCollectorProxyInterface $group): void {
    // создание одной или набора
    $group->post('', static function (Request $request, Response $response): Response {
        return $response;
    });

    // правка
    $group->patch('/{id}', static function (Request $request, Response $response): Response {
        return $response->withStatus(204);
    });

    // удаление
    $group->delete('/{id}', static function (Request $request, Response $response): Response {
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

return $app;