<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613140346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parameter ADD type VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE parameter SET type = "bool" WHERE code="user_confirmation_auto"');
        $this->addSql('UPDATE parameter SET type = "number" WHERE code="reward_sponsorship"');
        $this->addSql('UPDATE parameter SET type = "number" WHERE code="player_initial_budget"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parameter DROP type');
    }
}
