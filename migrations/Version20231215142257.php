<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215142257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT fk_cc6f9ec69291498c');
        $this->addSql('DROP TABLE ordre');
        $this->addSql('DROP INDEX idx_cc6f9ec69291498c');
        $this->addSql('ALTER TABLE detail_heures RENAME COLUMN ordre_id TO ordre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE ordre (id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE detail_heures RENAME COLUMN ordre TO ordre_id');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT fk_cc6f9ec69291498c FOREIGN KEY (ordre_id) REFERENCES ordre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cc6f9ec69291498c ON detail_heures (ordre_id)');
    }
}
