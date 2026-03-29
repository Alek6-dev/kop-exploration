<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405135527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Denormalize betting_round';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE betting_round_driver (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_48FC3A10D17F50A6 (uuid), INDEX IDX_48FC3A10C3423909 (driver_id), INDEX IDX_48FC3A1040C6CB55 (betting_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE betting_round_team (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_66F74266D17F50A6 (uuid), INDEX IDX_66F74266296CD8AE (team_id), UNIQUE INDEX UNIQ_66F7426640C6CB55 (betting_round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE betting_round_driver ADD CONSTRAINT FK_48FC3A10C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE betting_round_driver ADD CONSTRAINT FK_48FC3A1040C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id)');
        $this->addSql('ALTER TABLE betting_round_team ADD CONSTRAINT FK_66F74266296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE betting_round_team ADD CONSTRAINT FK_66F7426640C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id)');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B7C3423909');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B799E6F5DF');
        $this->addSql('ALTER TABLE betting_round_player_driver DROP FOREIGN KEY FK_2351A6B740C6CB55');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C99E6F5DF');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C40C6CB55');
        $this->addSql('ALTER TABLE betting_round_player_team DROP FOREIGN KEY FK_A77DA05C296CD8AE');
        $this->addSql('DROP TABLE betting_round_player_driver');
        $this->addSql('DROP TABLE betting_round_player_team');
        $this->addSql('ALTER TABLE betting_round DROP FOREIGN KEY FK_E5D7660A94DDBCE9');
        $this->addSql('DROP INDEX UNIQ_E5D7660A94DDBCE9 ON betting_round');
        $this->addSql('ALTER TABLE betting_round ADD is_set_by_system TINYINT(1) NOT NULL, DROP current_round_end_date, CHANGE championship_id player_id INT DEFAULT NULL, CHANGE current_round round INT NOT NULL');
        $this->addSql('ALTER TABLE betting_round ADD CONSTRAINT FK_E5D7660A99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_E5D7660A99E6F5DF ON betting_round (player_id)');
        $this->addSql('ALTER TABLE championship ADD current_round INT NOT NULL, ADD current_round_end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE championship_race ADD is_completed TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE betting_round_player_driver (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, player_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, is_set_by_system TINYINT(1) NOT NULL, round INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2351A6B740C6CB55 (betting_round_id), INDEX IDX_2351A6B799E6F5DF (player_id), INDEX IDX_2351A6B7C3423909 (driver_id), UNIQUE INDEX UNIQ_2351A6B7D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE betting_round_player_team (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, player_id INT DEFAULT NULL, betting_round_id INT DEFAULT NULL, bid_amount INT NOT NULL, is_set_by_system TINYINT(1) NOT NULL, round INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_A77DA05C40C6CB55 (betting_round_id), INDEX IDX_A77DA05C99E6F5DF (player_id), INDEX IDX_A77DA05C296CD8AE (team_id), UNIQUE INDEX UNIQ_A77DA05CD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B7C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B799E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_player_driver ADD CONSTRAINT FK_2351A6B740C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C40C6CB55 FOREIGN KEY (betting_round_id) REFERENCES betting_round (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_player_team ADD CONSTRAINT FK_A77DA05C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE betting_round_driver DROP FOREIGN KEY FK_48FC3A10C3423909');
        $this->addSql('ALTER TABLE betting_round_driver DROP FOREIGN KEY FK_48FC3A1040C6CB55');
        $this->addSql('ALTER TABLE betting_round_team DROP FOREIGN KEY FK_66F74266296CD8AE');
        $this->addSql('ALTER TABLE betting_round_team DROP FOREIGN KEY FK_66F7426640C6CB55');
        $this->addSql('DROP TABLE betting_round_driver');
        $this->addSql('DROP TABLE betting_round_team');
        $this->addSql('ALTER TABLE betting_round DROP FOREIGN KEY FK_E5D7660A99E6F5DF');
        $this->addSql('DROP INDEX IDX_E5D7660A99E6F5DF ON betting_round');
        $this->addSql('ALTER TABLE betting_round ADD current_round_end_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP is_set_by_system, CHANGE player_id championship_id INT DEFAULT NULL, CHANGE round current_round INT NOT NULL');
        $this->addSql('ALTER TABLE betting_round ADD CONSTRAINT FK_E5D7660A94DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E5D7660A94DDBCE9 ON betting_round (championship_id)');
        $this->addSql('ALTER TABLE championship DROP current_round, DROP current_round_end_date');
        $this->addSql('ALTER TABLE championship_race DROP is_completed');
    }
}
