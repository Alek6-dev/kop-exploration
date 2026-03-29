<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221140011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update unique indexes to use them';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_visitor RENAME INDEX uniq_92e8ff9586cc499d TO visitor_pseudo_idx');
        $this->addSql('ALTER TABLE user_visitor RENAME INDEX uniq_92e8ff95e7927c74 TO visitor_email_idx');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_visitor RENAME INDEX visitor_email_idx TO UNIQ_92E8FF95E7927C74');
        $this->addSql('ALTER TABLE user_visitor RENAME INDEX visitor_pseudo_idx TO UNIQ_92E8FF9586CC499D');
    }
}
