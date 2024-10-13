<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013000030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projet ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tache ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(64) NOT NULL, ADD created_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP created_at');
        $this->addSql('ALTER TABLE projet DROP created_at');
        $this->addSql('ALTER TABLE tache DROP created_at');
        $this->addSql('ALTER TABLE user DROP username, DROP created_at');
    }
}
