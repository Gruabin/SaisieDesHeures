<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102092942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_heures ADD employe_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC61B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CC6F9EC61B65292 ON detail_heures (employe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC61B65292');
        $this->addSql('DROP INDEX IDX_CC6F9EC61B65292');
        $this->addSql('ALTER TABLE detail_heures DROP employe_id');
    }
}
