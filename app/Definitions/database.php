<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Core\Data\Doctrine\EntityManagerBuilder;
use Cycle\Database\Config;
use Cycle\Database\DatabaseManager;
use Cycle\Database\Config\DatabaseConfig;
use Core\Data\Cycle\Facade\ConnectorFacade;
use Symfony\Component\Finder\Finder;
use Spiral\Tokenizer\ClassLocator;
use Cycle\Schema;
use Cycle\Annotated;
use Cycle\ORM;
use Cycle\ORM\Entity\Behavior\EventDrivenCommandGenerator;
use Cycle\ORM\EntityManager as CycleEntityManager;
use Cycle\Database\Config\DriverConfig;
use function Core\functions\inTesting;
use Core\Decorators\ReopeningEntityManagerDecorator;

return [
    ReopeningEntityManagerDecorator::class => static fn(
    ContainerInterface $container
) => new ReopeningEntityManagerDecorator($container),

    EntityManagerInterface::class => static fn(
    ContainerInterface $container
) => $container->get(ReopeningEntityManagerDecorator::class),

    DatabaseManager::class => static function (ContainerInterface $container): DatabaseManager {
        $getProductionConnection = static function (ContainerInterface $container): ?DriverConfig {
            if (!inTesting()) {
                $connectorFacade = new ConnectorFacade(
                    connection: $container->get("connection"),
                    connectionOptions: []
                );

                // Configure connector as you wish
                $connectorFacade
                    ->configureFactory()
                    ->withQueryCache(true)
                    ->withSchema("public");

                return $connectorFacade->produceDriverConnection(
                    driverOptions: []
                );
            }

            return null;
        };

        return new DatabaseManager(
            new DatabaseConfig([
                "default" => "default",
                "databases" => [
                    "default" => [
                        "connection" => inTesting() ? "sqlite" : "production",
                    ],
                ],
                "connections" => [
                    "sqlite" => new Config\SQLiteDriverConfig(
                        connection: new Config\SQLite\MemoryConnectionConfig(),
                        queryCache: true
                    ),
                    "production" => $getProductionConnection($container),
                ],
            ])
        );
    },
    ORM\ORM::class => function (ContainerInterface $container) {
        $root = $container->get('root');

        $finder = (new Finder())
            ->files()
            ->in([
                $root . "/src/Data/Entities/Cycle",
                $root . "/src/Data/Entities/Cycle/Rbac",
            ]);
        $classLocator = new ClassLocator($finder);
        $database = $container->get(DatabaseManager::class);
        $schemaCompiler = new Schema\Compiler();

        $schema = $schemaCompiler->compile(new Schema\Registry($database), [
            new Schema\Generator\ResetTables(),
                // re-declared table schemas (remove columns)
            new Annotated\Embeddings($classLocator),
                // register embeddable entities
            new Annotated\Entities($classLocator),
                // register annotated entities
            new Annotated\TableInheritance(),
                // register STI/JTI
            new Annotated\MergeColumns(),
                // add @Table column declarations
            new Schema\Generator\GenerateRelations(),
                // generate entity relations
            new Schema\Generator\GenerateModifiers(),
                // generate changes from schema modifiers
            new Schema\Generator\ValidateEntities(),
                // make sure all entity schemas are correct
            new Schema\Generator\RenderTables(),
                // declare table schemas
            new Schema\Generator\RenderRelations(),
                // declare relation keys and indexes
            new Schema\Generator\RenderModifiers(),
                // render all schema modifiers
            new Annotated\MergeIndexes(),
                // add @Table column declarations
            new Schema\Generator\SyncTables(),
                // sync table changes to database
            new Schema\Generator\GenerateTypecast(), // typecast non string columns
        ]);
        $schema = new ORM\Schema($schema);
        $commandGenerator = new EventDrivenCommandGenerator(
            $schema,
            $container
        );

        $orm = new ORM\ORM(
            new ORM\Factory($database),
            $schema,
            $commandGenerator
        );

        return $orm;
    },

    CycleEntityManager::class => static fn(
    ContainerInterface $container
) => new CycleEntityManager($container->get(ORM\ORM::class)),
];