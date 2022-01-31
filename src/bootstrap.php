<?php

declare(strict_types=1);

use App\Middleware;
use DI\Bridge\Slim\Bridge;
use Slim\Interfaces\RouteCollectorProxyInterface;
use App\Controller;
use App\Controller\Link;
use App\Controller\Stats;

require_once __DIR__ . '/../vendor/autoload.php';

$validationConfigs = include __DIR__ . '/../config/validations.php';

$app = Bridge::create();

$app->addBodyParsingMiddleware();

// работа со ссылками
$app->group('/links', static function (RouteCollectorProxyInterface $group) use ($validationConfigs): void {
    // создание одной или набора
    $group->post('', Link\Create::class)
        ->add(new Middleware\LinkValidator($validationConfigs['multiple']))
        ->add(new Middleware\SingleToMultidimention())
    ;
    // правка
    $group->patch('/{id}', Link\Update::class)
        ->add(new Middleware\LinkValidator($validationConfigs['single']))
    ;
    // удаление
    $group->delete('/{id}', Link\Delete::class);
    // получаем запись
    $group->get('/{id}', Link\View::class);
    // получаем все записи
    $group->get('', Link\Enumeration::class);
});

$app->group('/stats', static function (RouteCollectorProxyInterface $group): void {
    $group->get('', Stats\ViewAll::class);
    $group->get('/{id}', Stats\ViewItem::class);
});

// редирект на конечный ресурс
$app->get('/{id}', Controller\Redirect::class)
    ->add(Middleware\UpdateLinkStatistic::class)
;

return $app;
