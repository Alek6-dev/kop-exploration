<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515120816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel ADD points_player2 VARCHAR(3) DEFAULT NULL, DROP position, DROP score, CHANGE points points_player1 VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE duel_team_performance DROP INDEX UNIQ_7361792758875E, ADD INDEX IDX_7361792758875E (duel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel ADD position INT DEFAULT NULL, ADD score INT DEFAULT NULL, ADD points VARCHAR(3) DEFAULT NULL, DROP points_player1, DROP points_player2');
        $this->addSql('ALTER TABLE duel_team_performance DROP INDEX IDX_7361792758875E, ADD UNIQUE INDEX UNIQ_7361792758875E (duel_id)');
    }
}
