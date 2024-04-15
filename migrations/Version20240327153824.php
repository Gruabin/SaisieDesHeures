<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327153824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE detail_heures_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE detail_heures (id UUID NOT NULL, type_heures_id INT NOT NULL,ordre VARCHAR(255) DEFAULT NULL, tache_id INT DEFAULT NULL, activite_id INT DEFAULT NULL, centre_de_charge_id VARCHAR(255) DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, temps_main_oeuvre NUMERIC(5, 2) NOT NULL, employe_id VARCHAR(255) NOT NULL, operation INT DEFAULT NULL, date_export TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tache_specifique_id VARCHAR(255) DEFAULT NULL,  motif_erreur VARCHAR(255) DEFAULT NULL, statut_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CC6F9EC6649D60F6 ON detail_heures (type_heures_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC6D2235D39 ON detail_heures (tache_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC69B0F88B1 ON detail_heures (activite_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC651C2A904 ON detail_heures (centre_de_charge_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC61B65292 ON detail_heures (employe_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC68DC5A8C1 ON detail_heures (tache_specifique_id)');
        $this->addSql('CREATE INDEX IDX_CC6F9EC6F6203804 ON detail_heures (statut_id)');
        $this->addSql('COMMENT ON COLUMN detail_heures.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC6649D60F6 FOREIGN KEY (type_heures_id) REFERENCES type_heures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC6D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC69B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC651C2A904 FOREIGN KEY (centre_de_charge_id) REFERENCES centre_de_charge (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC61B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC68DC5A8C1 FOREIGN KEY (tache_specifique_id) REFERENCES tache_specifique (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE detail_heures ADD CONSTRAINT FK_CC6F9EC6F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE detail_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE detail_heures_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC6649D60F6');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC6D2235D39');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC69B0F88B1');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC651C2A904');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC61B65292');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC68DC5A8C1');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC6F6203804');
        $this->addSql('DROP TABLE detail_heures');
    }
}
