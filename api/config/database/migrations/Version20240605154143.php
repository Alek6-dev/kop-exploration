<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605154143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO parameter (uuid, label, code, value) VALUES (UNHEX(REPLACE('%s', '-', '')),'Récompense point KOP parrainage', 'reward_sponsorship', '20')", $uuid));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
