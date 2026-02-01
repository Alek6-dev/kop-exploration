<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604134126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add credit pack';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $uuid = (string) new UuidV4();
        $this->addSql("INSERT INTO credit_pack (uuid, product_id, credit, price, message) VALUES (UNHEX(REPLACE('$uuid', '-', '')), 'product_tmp_100', 100, 0.99, null)");
        $uuid = (string) new UuidV4();
        $this->addSql("INSERT INTO credit_pack (uuid, product_id, credit, price, message) VALUES (UNHEX(REPLACE('$uuid', '-', '')), 'product_tmp_250', 250, 2.35, 'économisez 5%')");
        $uuid = (string) new UuidV4();
        $this->addSql("INSERT INTO credit_pack (uuid, product_id, credit, price, message) VALUES (UNHEX(REPLACE('$uuid', '-', '')), 'product_tmp_500', 500, 4.49, 'économisez 10%')");
        $uuid = (string) new UuidV4();
        $this->addSql("INSERT INTO credit_pack (uuid, product_id, credit, price, message) VALUES (UNHEX(REPLACE('$uuid', '-', '')), 'product_tmp_1000', 1000, 8.49, 'économisez 15%')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
