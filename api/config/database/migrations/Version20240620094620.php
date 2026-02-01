<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620094620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add bonus tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, example VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, price INT NOT NULL, type VARCHAR(255) NOT NULL, target_type VARCHAR(255) NOT NULL, sub_target_type VARCHAR(255) NOT NULL, attribute VARCHAR(255) DEFAULT NULL, operation VARCHAR(255) DEFAULT NULL, value INT DEFAULT NULL, is_joker TINYINT(1) NOT NULL, is_enabled TINYINT(1) NOT NULL, sort INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_9F987F7AD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bonus_application (id INT AUTO_INCREMENT NOT NULL, bonus_id INT DEFAULT NULL, target_id INT DEFAULT NULL, championship_id INT DEFAULT NULL, player_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_9E11E56DD17F50A6 (uuid), INDEX IDX_9E11E56D69545666 (bonus_id), INDEX IDX_9E11E56D158E0B66 (target_id), INDEX IDX_9E11E56D94DDBCE9 (championship_id), INDEX IDX_9E11E56D99E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D69545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id)');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D158E0B66 FOREIGN KEY (target_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D94DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D69545666');
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D158E0B66');
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D94DDBCE9');
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D99E6F5DF');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE bonus_application');
    }
}
