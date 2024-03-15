<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011083747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE employe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tache_id_seq CASCADE');
        $this->addSql('CREATE TABLE centre_de_charge (id VARCHAR(255) NOT NULL, description_cdg VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE employe ADD centre_de_charge_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE employe ADD nom_employe VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE employe DROP nom');
        $this->addSql('ALTER TABLE employe DROP prenom');
        $this->addSql('ALTER TABLE employe DROP age');
        $this->addSql('ALTER TABLE employe DROP email');
        $this->addSql('ALTER TABLE employe ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B951C2A904 FOREIGN KEY (centre_de_charge_id) REFERENCES centre_de_charge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F804D3B951C2A904 ON employe (centre_de_charge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE employe DROP CONSTRAINT FK_F804D3B951C2A904');
        $this->addSql('CREATE SEQUENCE employe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tache_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE centre_de_charge');
        $this->addSql('DROP INDEX IDX_F804D3B951C2A904');
        $this->addSql('ALTER TABLE employe ADD prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE employe ADD age VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE employe ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE employe DROP centre_de_charge_id');
        $this->addSql('ALTER TABLE employe ALTER id TYPE INT');
        $this->addSql('ALTER TABLE employe RENAME COLUMN nom_employe TO nom');
    }
}
