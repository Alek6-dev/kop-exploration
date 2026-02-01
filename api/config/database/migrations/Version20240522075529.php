<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522075529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel_driver_performance CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy_driver_performance CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy_team_performance CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance CHANGE points points INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE duel_driver_performance CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy_driver_performance CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy_team_performance CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance CHANGE points points VARCHAR(3) DEFAULT NULL');
    }
}
