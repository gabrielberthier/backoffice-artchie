<?php
namespace Core\Data\BehaviourComponents;

use Cycle\Database\DatabaseManager;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class DatabaseCleaner
{
    public static function truncate(ContainerInterface $containerInterface): void
    {
        if (boolval(getenv("RR"))) {
            DatabaseCleaner::truncateCycleDatabase($containerInterface);
        } else {
            DatabaseCleaner::truncateDoctrineDatabase($containerInterface);
        }
    }

    public static function truncateDoctrineDatabase(ContainerInterface $containerInterface): void
    {
        /** @var EntityManager */
        $entityManager = $containerInterface->get(EntityManager::class);
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
    }

    public static function truncateCycleDatabase(ContainerInterface $containerInterface): void
    {
        /** @var DatabaseManager */
        $dbal = $containerInterface->get(DatabaseManager::class);
        $db = $dbal->database('default');

        // delete all FKs first
        foreach ($db->getTables() as $table) {
            $schema = $table->getSchema();
            foreach ($schema->getForeignKeys() as $foreign) {
                $schema->dropForeignKey($foreign->getColumns());
            }

            $schema->save(\Cycle\Database\Driver\HandlerInterface::DROP_FOREIGN_KEYS);
        }

        // delete tables
        foreach ($db->getTables() as $table) {
            $schema = $table->getSchema();
            $schema->declareDropped();
            $schema->save();
        }
    }
}