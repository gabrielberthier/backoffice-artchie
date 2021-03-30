<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;
use Tests\Traits\App\AppTestTrait;
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
    use AppTestTrait, DoublesTrait, ErrorHandlerTrait, InstanceManagerTrait, RequestManagerTrait;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var App
     */
    protected $app;
}
