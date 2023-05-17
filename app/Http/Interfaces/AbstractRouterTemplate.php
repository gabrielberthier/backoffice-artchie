<?php
declare(strict_types=1);

namespace Core\Http\Interfaces;


use Closure;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

abstract class AbstractRouterTemplate
{
  public function __construct(private App $app)
  {
  }

  public function run()
  {
    $this->prepareOnTheFlyRequests();
    $this->define($this->app);
  }

  public abstract function define(App $app): void;

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