<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402105405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE race ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE season ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE team ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP is_archived');
        $this->addSql('ALTER TABLE race DROP is_archived');
        $this->addSql('ALTER TABLE season DROP is_archived');
        $this->addSql('ALTER TABLE team DROP is_archived');
    }
}
