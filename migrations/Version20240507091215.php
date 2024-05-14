<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507091215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE detail_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE favori_type_heure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE favori_type_heure (id INT NOT NULL, type_heure_id INT DEFAULT NULL, employe_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1889A13F581417 ON favori_type_heure (type_heure_id)');
        $this->addSql('CREATE INDEX IDX_C1889A11B65292 ON favori_type_heure (employe_id)');
        $this->addSql('ALTER TABLE favori_type_heure ADD CONSTRAINT FK_C1889A13F581417 FOREIGN KEY (type_heure_id) REFERENCES type_heures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favori_type_heure ADD CONSTRAINT FK_C1889A11B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE site_tache_specifique ADD CONSTRAINT FK_5E8861C9F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE site_tache_specifique ADD CONSTRAINT FK_5E8861C98DC5A8C1 FOREIGN KEY (tache_specifique_id) REFERENCES tache_specifique (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E8861C9F6BD1646 ON site_tache_specifique (site_id)');
        $this->addSql('CREATE INDEX IDX_5E8861C98DC5A8C1 ON site_tache_specifique (tache_specifique_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE favori_type_heure_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE detail_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE favori_type_heure DROP CONSTRAINT FK_C1889A13F581417');
        $this->addSql('ALTER TABLE favori_type_heure DROP CONSTRAINT FK_C1889A11B65292');
        $this->addSql('DROP TABLE favori_type_heure');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC651C2A904');
        $this->addSql('ALTER TABLE detail_heures DROP CONSTRAINT FK_CC6F9EC61B65292');
        $this->addSql('ALTER TABLE employe DROP CONSTRAINT FK_F804D3B951C2A904');
        $this->addSql('ALTER TABLE employe ALTER roles SET DEFAULT \'["ROLE_EMPLOYE"]\'');
        $this->addSql('ALTER TABLE site_tache_specifique DROP CONSTRAINT FK_5E8861C9F6BD1646');
        $this->addSql('ALTER TABLE site_tache_specifique DROP CONSTRAINT FK_5E8861C98DC5A8C1');
        $this->addSql('DROP INDEX IDX_5E8861C9F6BD1646');
        $this->addSql('DROP INDEX IDX_5E8861C98DC5A8C1');
    }
}
