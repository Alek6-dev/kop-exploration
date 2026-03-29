<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530103917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add score with bonus on driver performances';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance ADD score_with_bonus INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel_driver_performance ADD score_with_bonus INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD score_with_bonus INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance DROP score_with_bonus');
        $this->addSql('ALTER TABLE duel_driver_performance DROP score_with_bonus');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP score_with_bonus');
    }
}
