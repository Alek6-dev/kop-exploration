<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604132019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add credit wallet for existing users';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $users = $this->connection->fetchAllAssociative('SELECT * FROM user_visitor');
        foreach ($users as $user) {
            $uuid = (string) new UuidV4();
            $this->addSql(sprintf("INSERT INTO credit_wallet (uuid, user_id, credit) VALUES (UNHEX(REPLACE('%s', '-', '')), %s, 0)", $uuid, $user['id']));
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
