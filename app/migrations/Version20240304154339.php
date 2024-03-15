<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304154339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centre_de_charge DROP CONSTRAINT fk_b9dd5f996ea32074');
        $this->addSql('DROP INDEX idx_b9dd5f996ea32074');
        $this->addSql('ALTER TABLE centre_de_charge RENAME COLUMN id_responsable_id TO responsable_id');
        $this->addSql('ALTER TABLE centre_de_charge ADD CONSTRAINT FK_B9DD5F9953C59D72 FOREIGN KEY (responsable_id) REFERENCES employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B9DD5F9953C59D72 ON centre_de_charge (responsable_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE centre_de_charge DROP CONSTRAINT FK_B9DD5F9953C59D72');
        $this->addSql('DROP INDEX IDX_B9DD5F9953C59D72');
        $this->addSql('ALTER TABLE centre_de_charge RENAME COLUMN responsable_id TO id_responsable_id');
        $this->addSql('ALTER TABLE centre_de_charge ADD CONSTRAINT fk_b9dd5f996ea32074 FOREIGN KEY (id_responsable_id) REFERENCES employe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b9dd5f996ea32074 ON centre_de_charge (id_responsable_id)');
    }
}
