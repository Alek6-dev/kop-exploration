<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404071746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE betting_round (id INT AUTO_INCREMENT NOT NULL, championship_id INT DEFAULT NULL, current_round INT NOT NULL, current_round_end_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_E5D7660AD17F50A6 (uuid), UNIQUE INDEX UNIQ_E5D7660A94DDBCE9 (championship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE betting_round_player_driver (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, player_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, is_set_by_system TINYINT(1) NOT NULL, round INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_2351A6B7D17F50A6 (uuid), INDEX IDX_2351A6B7C3423909 (driver_id), INDEX IDX_2351A6B799E6F5DF (player_id), INDEX IDX_2351A6B740C6CB55 (betting_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE betting_round_player_team (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, player_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, is_set_by_system TINYINT(1) NOT NULL, round INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_A77DA05CD17F50A6 (uuid), INDEX IDX_A77DA05C296CD8AE (team_id), INDEX IDX_A77DA05C99E6F5DF (player_id), INDEX IDX_A77DA05C40C6CB55 (betting_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE betting_round ADD CONSTRAINT FK_E5D7660A94DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B7C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B799E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B740C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id)');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C40C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id)');
        $this->addSql('ALTER TABLE championship ADD initial_budget INT NOT NULL, ADD initial_usage_driver INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD name VARCHAR(255) NOT NULL');

        $this->addSql('DELETE FROM parameter WHERE code="player_initial_usage_driver"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE betting_round DROP FOREIGN KEY FK_E5D7660A94DDBCE9');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B7C3423909');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B799E6F5DF');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B740C6CB55');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C296CD8AE');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C99E6F5DF');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C40C6CB55');
        $this->addSql('DROP TABLE betting_round');
        $this->addSql('DROP TABLE betting_round_player_driver');
        $this->addSql('DROP TABLE betting_round_player_team');
        $this->addSql('ALTER TABLE championship DROP initial_budget, DROP initial_usage_driver');
        $this->addSql('ALTER TABLE player DROP name');
    }
}
