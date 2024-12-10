<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210083239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log_message (id SERIAL NOT NULL, company_id INT DEFAULT NULL, account_id INT DEFAULT NULL, message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8E7008E8979B1AD6 ON log_message (company_id)');
        $this->addSql('CREATE INDEX IDX_8E7008E89B6B5FBA ON log_message (account_id)');
        $this->addSql('ALTER TABLE log_message ADD CONSTRAINT FK_8E7008E8979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE log_message ADD CONSTRAINT FK_8E7008E89B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE log_message DROP CONSTRAINT FK_8E7008E8979B1AD6');
        $this->addSql('ALTER TABLE log_message DROP CONSTRAINT FK_8E7008E89B6B5FBA');
        $this->addSql('DROP TABLE log_message');
    }
}
