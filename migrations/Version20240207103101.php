<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207103101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE site (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE site_tache_specifique (site_id INT NOT NULL, tache_specifique_id INT NOT NULL, PRIMARY KEY(site_id, tache_specifique_id))');
        $this->addSql('CREATE INDEX IDX_5E8861C9F6BD1646 ON site_tache_specifique (site_id)');
        $this->addSql('CREATE INDEX IDX_5E8861C98DC5A8C1 ON site_tache_specifique (tache_specifique_id)');
        $this->addSql('CREATE TABLE tache_specifique (id INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE site_tache_specifique ADD CONSTRAINT FK_5E8861C9F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE site_tache_specifique ADD CONSTRAINT FK_5E8861C98DC5A8C1 FOREIGN KEY (tache_specifique_id) REFERENCES tache_specifique (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD tache_specifique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC68DC5A8C1 FOREIGN KEY (tache_specifique_id) REFERENCES tache_specifique (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CC6F9EC68DC5A8C1 ON detail_heures (tache_specifique_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC68DC5A8C1');
        $this->addSql('ALTER TABLE site_tache_specifique DROP CONSTRAINT FK_5E8861C9F6BD1646');
        $this->addSql('ALTER TABLE site_tache_specifique DROP CONSTRAINT FK_5E8861C98DC5A8C1');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_tache_specifique');
        $this->addSql('DROP TABLE tache_specifique');
        $this->addSql('DROP INDEX IDX_CC6F9EC68DC5A8C1');
        $this->addSql('ALTER TABLE detail_heures DROP tache_specifique_id');
    }
}
