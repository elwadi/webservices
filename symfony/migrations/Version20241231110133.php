<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231110133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id SERIAL NOT NULL, website_id INT DEFAULT NULL, product_id VARCHAR(255) NOT NULL, product_name VARCHAR(255) NOT NULL, product_description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04AD18F45C82 ON product (website_id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD18F45C82 FOREIGN KEY (website_id) REFERENCES wp_site (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD18F45C82');
        $this->addSql('DROP TABLE product');
    }
}
