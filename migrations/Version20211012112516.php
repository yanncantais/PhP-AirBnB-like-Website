<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211012112516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, created DATETIME NOT NULL, content_type VARCHAR(255) DEFAULT NULL)');
        $this->addSql('DROP INDEX IDX_AB3946AC54177093');
        $this->addSql('DROP INDEX IDX_AB3946AC98260155');
        $this->addSql('CREATE TEMPORARY TABLE __temp__region_room AS SELECT region_id, room_id FROM region_room');
        $this->addSql('DROP TABLE region_room');
        $this->addSql('CREATE TABLE region_room (region_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(region_id, room_id), CONSTRAINT FK_AB3946AC98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AB3946AC54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO region_room (region_id, room_id) SELECT region_id, room_id FROM __temp__region_room');
        $this->addSql('DROP TABLE __temp__region_room');
        $this->addSql('CREATE INDEX IDX_AB3946AC54177093 ON region_room (room_id)');
        $this->addSql('CREATE INDEX IDX_AB3946AC98260155 ON region_room (region_id)');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER DEFAULT NULL, summary VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) NOT NULL COLLATE BINARY, address VARCHAR(255) NOT NULL COLLATE BINARY, capacity VARCHAR(255) DEFAULT NULL, superficy VARCHAR(255) DEFAULT NULL, price VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_729F519B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE paste');
        $this->addSql('DROP INDEX IDX_AB3946AC98260155');
        $this->addSql('DROP INDEX IDX_AB3946AC54177093');
        $this->addSql('CREATE TEMPORARY TABLE __temp__region_room AS SELECT region_id, room_id FROM region_room');
        $this->addSql('DROP TABLE region_room');
        $this->addSql('CREATE TABLE region_room (region_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(region_id, room_id))');
        $this->addSql('INSERT INTO region_room (region_id, room_id) SELECT region_id, room_id FROM __temp__region_room');
        $this->addSql('DROP TABLE __temp__region_room');
        $this->addSql('CREATE INDEX IDX_AB3946AC98260155 ON region_room (region_id)');
        $this->addSql('CREATE INDEX IDX_AB3946AC54177093 ON region_room (room_id)');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER DEFAULT NULL, summary VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, capacity VARCHAR(255) NOT NULL COLLATE BINARY, superficy VARCHAR(255) NOT NULL COLLATE BINARY, price VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
    }
}
