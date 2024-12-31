<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231104440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wp_site (id SERIAL NOT NULL, account_id INT DEFAULT NULL, websiteurl VARCHAR(255) NOT NULL, cs_key VARCHAR(255) NOT NULL, cs_secret VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DC5B2F89B6B5FBA ON wp_site (account_id)');
        $this->addSql('ALTER TABLE wp_site ADD CONSTRAINT FK_4DC5B2F89B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wp_site DROP CONSTRAINT FK_4DC5B2F89B6B5FBA');
        $this->addSql('DROP TABLE wp_site');
    }
}
