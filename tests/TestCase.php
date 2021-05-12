<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Tests\Traits\App\AppTestTrait;
use Tests\Traits\App\DatabaseManagerTrait;
use Tests\Traits\App\DoublesTrait;
use Tests\Traits\App\ErrorHandlerTrait;
use Tests\Traits\App\InstanceManagerTrait;
use Tests\Traits\App\RequestManagerTrait;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends PHPUnit_TestCase
{
    use AppTestTrait;
    use DoublesTrait;
    use ErrorHandlerTrait;
    use InstanceManagerTrait;
    use RequestManagerTrait;
    use DatabaseManagerTrait;

    /**
     * @var Container
     */
    protected ContainerInterface $container;

    /**
     * @var App
     */
    protected $app;
}
