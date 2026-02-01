<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226151610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert parameters';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO parameter (label, code, value) VALUES ('Budget initial des joueurs', 'player_initial_budget', '350')");
        $this->addSql("INSERT INTO parameter (label, code, value) VALUES ('Nombre d\'utilisation initial des pilotes', 'player_initial_usage_driver', '6')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
