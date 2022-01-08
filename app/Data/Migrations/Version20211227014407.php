<?php

declare(strict_types=1);

namespace Core\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227014407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE accounts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE assets_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE markers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE museums_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE placement_objects_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE signature_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE accounts (id INT NOT NULL, uuid UUID NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC89EACD17F50A6 ON accounts (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC89EACE7927C74 ON accounts (email)');
        $this->addSql('CREATE TABLE assets (id INT NOT NULL, path VARCHAR(255) NOT NULL, fileName VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, mediaType VARCHAR(255) NOT NULL, originalName VARCHAR(255) NOT NULL, mimeType VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79D17D8EB548B0F ON assets (path)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79D17D8E9C39465B ON assets (fileName)');
        $this->addSql('CREATE TABLE marker_assets (marker_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(marker_id))');
        $this->addSql('CREATE TABLE markers (id INT NOT NULL, museum_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, text TEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, isActive BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4189DF304B52E5B5 ON markers (museum_id)');
        $this->addSql('CREATE TABLE museums (id INT NOT NULL, uuid UUID NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, info VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D50D6CD0D17F50A6 ON museums (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D50D6CD0E7927C74 ON museums (email)');
        $this->addSql('CREATE TABLE placement_objects (id INT NOT NULL, marker_id INT DEFAULT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, isActive BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DF23F54D17F50A6 ON placement_objects (uuid)');
        $this->addSql('CREATE INDEX IDX_7DF23F54474460EB ON placement_objects (marker_id)');
        $this->addSql('CREATE TABLE posed_assets (placement_object_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(placement_object_id))');
        $this->addSql('CREATE TABLE signature_tokens (id INT NOT NULL, museum_id INT DEFAULT NULL, signature TEXT NOT NULL, privateKey TEXT NOT NULL, time_to_live TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_566BCE004B52E5B5 ON signature_tokens (museum_id)');
        $this->addSql('CREATE TABLE three_dimensional_assets (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE two_dimensional_assets (id INT NOT NULL, model_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_871BB3FC7975B7E7 ON two_dimensional_assets (model_id)');
        $this->addSql('ALTER TABLE markers ADD CONSTRAINT FK_4189DF304B52E5B5 FOREIGN KEY (museum_id) REFERENCES museums (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE placement_objects ADD CONSTRAINT FK_7DF23F54474460EB FOREIGN KEY (marker_id) REFERENCES markers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE signature_tokens ADD CONSTRAINT FK_566BCE004B52E5B5 FOREIGN KEY (museum_id) REFERENCES museums (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE three_dimensional_assets ADD CONSTRAINT FK_CFC69230BF396750 FOREIGN KEY (id) REFERENCES assets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE two_dimensional_assets ADD CONSTRAINT FK_871BB3FC7975B7E7 FOREIGN KEY (model_id) REFERENCES three_dimensional_assets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE two_dimensional_assets ADD CONSTRAINT FK_871BB3FCBF396750 FOREIGN KEY (id) REFERENCES assets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE three_dimensional_assets DROP CONSTRAINT FK_CFC69230BF396750');
        $this->addSql('ALTER TABLE two_dimensional_assets DROP CONSTRAINT FK_871BB3FCBF396750');
        $this->addSql('ALTER TABLE placement_objects DROP CONSTRAINT FK_7DF23F54474460EB');
        $this->addSql('ALTER TABLE markers DROP CONSTRAINT FK_4189DF304B52E5B5');
        $this->addSql('ALTER TABLE signature_tokens DROP CONSTRAINT FK_566BCE004B52E5B5');
        $this->addSql('ALTER TABLE two_dimensional_assets DROP CONSTRAINT FK_871BB3FC7975B7E7');
        $this->addSql('DROP SEQUENCE accounts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE assets_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE markers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE museums_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE placement_objects_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE signature_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE assets');
        $this->addSql('DROP TABLE marker_assets');
        $this->addSql('DROP TABLE markers');
        $this->addSql('DROP TABLE museums');
        $this->addSql('DROP TABLE placement_objects');
        $this->addSql('DROP TABLE posed_assets');
        $this->addSql('DROP TABLE signature_tokens');
        $this->addSql('DROP TABLE three_dimensional_assets');
        $this->addSql('DROP TABLE two_dimensional_assets');
    }
}
