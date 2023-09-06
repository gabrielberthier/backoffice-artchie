<?php

declare(strict_types=1);

namespace Tests;

use Core\Data\BehaviourComponents\DatabaseCleaner;
use Core\Data\BehaviourComponents\DatabaseCreator;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
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
    use AppTestTrait;
    use DoublesTrait;
    use ErrorHandlerTrait;
    use InstanceManagerTrait;
    use RequestManagerTrait;

    public static function createDatabase()
    {
        $container = self::requireContainer();

        DatabaseCreator::create($container);
    }

    final public static function truncateDatabase()
    {
        $container = self::requireContainer();

        DatabaseCleaner::truncate($container);
    }

    public static function createDatabaseDoctrine()
    {
        $container = self::requireContainer();

        DatabaseCreator::createDoctrineDatabase($container);
    }

    final public static function truncateDatabaseDoctrine()
    {
        $container = self::requireContainer();

        DatabaseCleaner::truncateDoctrineDatabase($container);
    }


}