<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828120554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE app_user ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app_user ADD location VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app_user ADD age VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE app_user ADD date VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_user DROP email');
        $this->addSql('ALTER TABLE app_user DROP location');
        $this->addSql('ALTER TABLE app_user DROP age');
        $this->addSql('ALTER TABLE app_user DROP date');
    }
}
