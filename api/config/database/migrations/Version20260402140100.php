<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402140100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, is_for_all TINYINT(1) DEFAULT 0 NOT NULL, scheduled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_BF5476CAD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_targets (notification_id INT NOT NULL, user_visitor_id INT NOT NULL, INDEX IDX_BA5D94A4EF1A9D84 (notification_id), INDEX IDX_BA5D94A4A06C2881 (user_visitor_id), PRIMARY KEY(notification_id, user_visitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_read (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, notification_id INT NOT NULL, read_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_206B0A5DD17F50A6 (uuid), INDEX IDX_206B0A5DA76ED395 (user_id), INDEX IDX_206B0A5DEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_targets ADD CONSTRAINT FK_BA5D94A4EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_targets ADD CONSTRAINT FK_BA5D94A4A06C2881 FOREIGN KEY (user_visitor_id) REFERENCES user_visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_read ADD CONSTRAINT FK_206B0A5DA76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_read ADD CONSTRAINT FK_206B0A5DEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_targets DROP FOREIGN KEY FK_BA5D94A4EF1A9D84');
        $this->addSql('ALTER TABLE notification_targets DROP FOREIGN KEY FK_BA5D94A4A06C2881');
        $this->addSql('ALTER TABLE notification_read DROP FOREIGN KEY FK_206B0A5DA76ED395');
        $this->addSql('ALTER TABLE notification_read DROP FOREIGN KEY FK_206B0A5DEF1A9D84');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_targets');
        $this->addSql('DROP TABLE notification_read');
    }
}
