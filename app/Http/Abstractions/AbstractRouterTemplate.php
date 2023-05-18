<?php
declare(strict_types=1);

namespace Core\Http\Abstractions;


use Closure;
use Core\Http\Interfaces\RouterInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

abstract class AbstractRouterTemplate implements RouterInterface
{
  public function __construct(private App $app)
  {
  }

  public function run(): void
  {
    $this->prepareOnTheFlyRequests();
    $this->collect($this->app);
  }

  protected function setGroup(string $domain, string $handlerPath)
  {
    return $this->app->group($domain, $this->getRouteGroup($handlerPath));
  }

  private function prepareOnTheFlyRequests()
  {
    $this->app->options(
      '/{routes:.+}',
      fn(Request $request, Response $response, $args) => $response->withStatus(200)
    );
  }

  private function getRouteGroup(string $path): Closure
  {
    return require __DIR__ . "/../routes/{$path}.php";
  }
}