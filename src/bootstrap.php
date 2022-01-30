<?php

declare(strict_types=1);

use App\Middleware\SingleToMultidimention;
use DI\Bridge\Slim\Bridge;
use Slim\Interfaces\RouteCollectorProxyInterface;
use App\Controller;
use App\Controller\Link;

require_once __DIR__ . '/../vendor/autoload.php';

$app = Bridge::create();

$app->addBodyParsingMiddleware();

// работа со ссылками
$app->group('/links', static function (RouteCollectorProxyInterface $group): void {
    // создание одной или набора
    $group->post('', Link\Create::class)->add(new SingleToMultidimention);
    // правка
    $group->patch('/{id}', Link\Update::class);
    // удаление
    $group->delete('/{id}', Link\Delete::class);
    // получаем запись
    $group->get('/{id}', Link\View::class);
    // получаем все записи
    $group->get('', Link\Enumeration::class);
});

// редирект на конечный ресурс
$app->get('/{id}', Controller\Redirect::class);

return $app;
