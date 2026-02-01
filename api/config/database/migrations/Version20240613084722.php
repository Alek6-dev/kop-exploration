<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613084722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change creation_status to status';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_admin CHANGE creation_status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_visitor CHANGE creation_status status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_admin CHANGE status creation_status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_visitor CHANGE status creation_status VARCHAR(255) NOT NULL');
    }
}
