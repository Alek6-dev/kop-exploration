<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520091035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cosmetic_possessed (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cosmetic_id INT DEFAULT NULL, is_selected TINYINT(1) NOT NULL, price INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_54AADD6DD17F50A6 (uuid), INDEX IDX_54AADD6DA76ED395 (user_id), INDEX IDX_54AADD6D6D112934 (cosmetic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cosmetic_possessed ADD CONSTRAINT FK_54AADD6DA76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id)');
        $this->addSql('ALTER TABLE cosmetic_possessed ADD CONSTRAINT FK_54AADD6D6D112934 FOREIGN KEY (cosmetic_id) REFERENCES cosmetic (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cosmetic_possessed DROP FOREIGN KEY FK_54AADD6DA76ED395');
        $this->addSql('ALTER TABLE cosmetic_possessed DROP FOREIGN KEY FK_54AADD6D6D112934');
        $this->addSql('DROP TABLE cosmetic_possessed');
    }
}
