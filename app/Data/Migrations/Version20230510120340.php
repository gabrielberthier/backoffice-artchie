<?php

declare(strict_types=1);

namespace Core\Data\Migrations;

use DateTimeImmutable;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510120340 extends AbstractMigration
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
        $this->addSql('CREATE TABLE accounts (id INT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC89EACE7927C74 ON accounts (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC89EACD17F50A6 ON accounts (uuid)');
        $this->addSql('COMMENT ON COLUMN accounts.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE assets (id INT NOT NULL, parent_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, fileName VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, mediaType VARCHAR(255) NOT NULL, originalName VARCHAR(255) NOT NULL, mimeType VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, asset_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79D17D8EB548B0F ON assets (path)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79D17D8E9C39465B ON assets (fileName)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79D17D8ED17F50A6 ON assets (uuid)');
        $this->addSql('CREATE INDEX IDX_79D17D8E727ACA70 ON assets (parent_id)');
        $this->addSql('COMMENT ON COLUMN assets.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE marker_assets (marker_id INT NOT NULL, asset_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(marker_id, asset_id))');
        $this->addSql('CREATE INDEX IDX_9F1ADF05474460EB ON marker_assets (marker_id)');
        $this->addSql('CREATE INDEX IDX_9F1ADF055DA1941 ON marker_assets (asset_id)');
        $this->addSql('CREATE TABLE markers (id INT NOT NULL, museum_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, text TEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, isActive BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4189DF30D17F50A6 ON markers (uuid)');
        $this->addSql('CREATE INDEX IDX_4189DF304B52E5B5 ON markers (museum_id)');
        $this->addSql('COMMENT ON COLUMN markers.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE museums (id INT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, info VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D50D6CD0E7927C74 ON museums (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D50D6CD0D17F50A6 ON museums (uuid)');
        $this->addSql('COMMENT ON COLUMN museums.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE placement_objects (id INT NOT NULL, marker_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, isActive BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DF23F54D17F50A6 ON placement_objects (uuid)');
        $this->addSql('CREATE INDEX IDX_7DF23F54474460EB ON placement_objects (marker_id)');
        $this->addSql('COMMENT ON COLUMN placement_objects.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE posed_assets (placement_object_id INT NOT NULL, asset_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(placement_object_id, asset_id))');
        $this->addSql('CREATE INDEX IDX_DE163427E6DF104B ON posed_assets (placement_object_id)');
        $this->addSql('CREATE INDEX IDX_DE1634275DA1941 ON posed_assets (asset_id)');
        $this->addSql('CREATE TABLE signature_tokens (id INT NOT NULL, museum_id INT DEFAULT NULL, signature TEXT NOT NULL, privateKey TEXT NOT NULL, time_to_live TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_566BCE004B52E5B5 ON signature_tokens (museum_id)');
        $this->addSql('ALTER TABLE assets ADD CONSTRAINT FK_79D17D8E727ACA70 FOREIGN KEY (parent_id) REFERENCES assets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marker_assets ADD CONSTRAINT FK_9F1ADF05474460EB FOREIGN KEY (marker_id) REFERENCES markers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE marker_assets ADD CONSTRAINT FK_9F1ADF055DA1941 FOREIGN KEY (asset_id) REFERENCES assets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE markers ADD CONSTRAINT FK_4189DF304B52E5B5 FOREIGN KEY (museum_id) REFERENCES museums (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE placement_objects ADD CONSTRAINT FK_7DF23F54474460EB FOREIGN KEY (marker_id) REFERENCES markers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posed_assets ADD CONSTRAINT FK_DE163427E6DF104B FOREIGN KEY (placement_object_id) REFERENCES placement_objects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posed_assets ADD CONSTRAINT FK_DE1634275DA1941 FOREIGN KEY (asset_id) REFERENCES assets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE signature_tokens ADD CONSTRAINT FK_566BCE004B52E5B5 FOREIGN KEY (museum_id) REFERENCES museums (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE accounts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE assets_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE markers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE museums_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE placement_objects_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE signature_tokens_id_seq CASCADE');
        $this->addSql('ALTER TABLE assets DROP CONSTRAINT FK_79D17D8E727ACA70');
        $this->addSql('ALTER TABLE marker_assets DROP CONSTRAINT FK_9F1ADF05474460EB');
        $this->addSql('ALTER TABLE marker_assets DROP CONSTRAINT FK_9F1ADF055DA1941');
        $this->addSql('ALTER TABLE markers DROP CONSTRAINT FK_4189DF304B52E5B5');
        $this->addSql('ALTER TABLE placement_objects DROP CONSTRAINT FK_7DF23F54474460EB');
        $this->addSql('ALTER TABLE posed_assets DROP CONSTRAINT FK_DE163427E6DF104B');
        $this->addSql('ALTER TABLE posed_assets DROP CONSTRAINT FK_DE1634275DA1941');
        $this->addSql('ALTER TABLE signature_tokens DROP CONSTRAINT FK_566BCE004B52E5B5');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE assets');
        $this->addSql('DROP TABLE marker_assets');
        $this->addSql('DROP TABLE markers');
        $this->addSql('DROP TABLE museums');
        $this->addSql('DROP TABLE placement_objects');
        $this->addSql('DROP TABLE posed_assets');
        $this->addSql('DROP TABLE signature_tokens');
    }
}