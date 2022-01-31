<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Entity\Visit;
use App\Repository\VisitRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;

class UpdateLinkStatistic
{
    public function __construct(private VisitRepositoryInterface $repository)
    {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($response->getStatusCode() === StatusCodeInterface::STATUS_FOUND) {
            $routeContext = RouteContext::fromRequest($request);
            /** @var RouteInterface $route */
            $route = $routeContext->getRoute();

            $this->repository->add(new Visit(
                $route->getArgument('id'),
                $request->getServerParams()['REMOTE_ADDR'],
                $request->getServerParams()['HTTP_USER_AGENT'],
                new \DateTimeImmutable((string) $request->getServerParams()['REQUEST_TIME'])
            ));
        }

        return $response;
    }
}
