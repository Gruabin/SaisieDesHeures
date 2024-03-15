<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122085256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT fk_cc6f9ec644ac3583');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP INDEX idx_cc6f9ec644ac3583');
        $this->addSql('ALTER TABLE detail_heures ADD operation INT NOT NULL');
        $this->addSql('ALTER TABLE detail_heures DROP operation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE detail_heures ADD operation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_heures DROP operation');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT fk_cc6f9ec644ac3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cc6f9ec644ac3583 ON detail_heures (operation_id)');
    }
}
