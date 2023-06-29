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
use Spiral\Core\Exception\ConfigException;
use Cycle\ORM;
use Cycle\ORM\EntityManager as CycleEntityManager;

return [
    EntityManagerInterface::class => static fn (
        ContainerInterface $container
    ) => EntityManagerBuilder::produce(
        $container->get("settings")["doctrine"]
    ),

    DatabaseManager::class => static function (ContainerInterface $container): DatabaseManager {
        $connectorFacade = new ConnectorFacade(
            connection: $container->get("connection")
        );

        // Configure connector as you wish
        $connectorFacade
            ->configureFactory()
            ->withQueryCache(true)
            ->withSchema("public");

        return new DatabaseManager(
            new DatabaseConfig([
                "default" => "default",
                "databases" => [
                    "default" => ["connection" => "production"],
                ],
                "connections" => [
                    "sqlite" => new Config\SQLiteDriverConfig(
                        connection: new Config\SQLite\MemoryConnectionConfig(),
                        queryCache: true
                    ),
                    "production" => $connectorFacade->produceDriverConnection(),
                ],
            ])
        );
    },
    ORM\ORM::class => function (ContainerInterface $container) {
        $root = dirname(dirname(__DIR__));
        $finder = (new Finder())->files()->in([$root . '/src/Data/Entities/Cycle']);
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

        $orm = new ORM\ORM(new ORM\Factory($database), new ORM\Schema($schema));

        return $orm;
    },

    CycleEntityManager::class => static fn (ContainerInterface $container) => new CycleEntityManager($container->get(ORM\ORM::class))
];
