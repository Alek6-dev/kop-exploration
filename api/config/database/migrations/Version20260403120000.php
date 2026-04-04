<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260403120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add show_as_popup column to notification table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification ADD show_as_popup TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification DROP show_as_popup');
    }
}
