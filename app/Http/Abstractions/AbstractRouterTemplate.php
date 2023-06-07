<?php
declare(strict_types=1);

namespace Core\Http\Abstractions;


use Closure;
use Core\Http\Interfaces\RouteCollectorInterface;
use Core\Http\Interfaces\RouterInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractRouterTemplate implements RouterInterface
{
  public function __construct(private RouteCollectorInterface $routeCollector)
  {
  }

  public function run(): void
  {
    $this->prepareOnTheFlyRequests();
    $this->collect($this->routeCollector);
  }

  protected function setGroup(string $domain, string $handlerPath)
  {
    return $this->routeCollector->group($domain, $this->getRouteGroup($handlerPath));
  }

  private function prepareOnTheFlyRequests()
  {
    $this->routeCollector->options(
      '/{routes:.+}',
      fn(Request $request, Response $response, $args) => $response->withStatus(200)
    );
  }

  private function getRouteGroup(string $path): Closure
  {
    return require __DIR__ . "/../routes/{$path}.php";
  }
}