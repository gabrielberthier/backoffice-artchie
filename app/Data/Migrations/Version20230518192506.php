<?php

declare(strict_types=1);

namespace Core\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518192506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add auth_type column to account';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accounts ADD auth_type VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accounts DROP auth_type');
    }
}
