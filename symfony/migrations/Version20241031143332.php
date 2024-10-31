<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031143332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE account ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7D3656A4979B1AD6 ON account (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A4979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP INDEX IDX_7D3656A4979B1AD6');
        $this->addSql('ALTER TABLE account DROP company_id');
    }
}
