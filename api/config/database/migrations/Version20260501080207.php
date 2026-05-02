<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260501080207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Clean up duplicate SeasonGPStrategy rows and add unique constraint on (participation_id, race_id)';
    }

    public function up(Schema $schema): void
    {
        // Keep only the latest strategy per participation+race (highest id wins)
        $this->addSql('
            DELETE s FROM season_gpstrategy s
            LEFT JOIN (
                SELECT MAX(id) as max_id
                FROM season_gpstrategy
                GROUP BY participation_id, race_id
            ) latest ON s.id = latest.max_id
            WHERE latest.max_id IS NULL
        ');

        $this->addSql('ALTER TABLE season_gpstrategy ADD UNIQUE INDEX UNIQ_strategy_participation_race (participation_id, race_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE season_gpstrategy DROP INDEX UNIQ_strategy_participation_race');
    }
}
