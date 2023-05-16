<?php

declare(strict_types=1);

namespace Core\Data\Migrations;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510120934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates a default account';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $date = new DateTimeImmutable(timezone: new DateTimeZone('America/Fortaleza'));
        $now = $date->format('Y-m-d H:i:sP');
        $fields = "id,email,username,password,role,uuid,created_at,updated_at";
        $values = array(
            "id" => 42,
            "email" => 'adming@arcthie.com',
            "username" => 'admin',
            "password" => '$2y$08$dBhkPXXqtiZoRyFQ5BIfaOVe2pbdd03lZaEUnux9pNcbGf4/5epoe',
            "role" => 'admin',
            "uuid" => '0c04d7f5-5e42-4fdd-9ba2-d1b44cd22ac9',
            "created_at" => $now,
            "updated_at" => $now
        );
        $this->addSql("INSERT into accounts ({$fields}) VALUES (:id,:email,:username,:password,:role,:uuid,:created_at,:updated_at)", $values);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM accounts where id = :id", ['id' => 42]);
    }
}