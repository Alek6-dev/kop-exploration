<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314124934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert parameters with valid uuid.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE parameter');
        $uuidv4 = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO parameter (uuid, label, code, value) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Budget initial des joueurs', 'player_initial_budget', '350')", $uuidv4));
        $uuidv4 = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO parameter (uuid, label, code, value) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Nombre d\'utilisation initial des pilotes', 'player_initial_usage_driver', '6')", $uuidv4));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
