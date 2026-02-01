<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417081801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add performance tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driver_performance (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, race_id INT DEFAULT NULL, season_id INT DEFAULT NULL, qualification_points INT NOT NULL, race_points INT NOT NULL, sprint_points INT NOT NULL, position_gain INT NOT NULL, position VARCHAR(3) NOT NULL, score INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_8E05C933D17F50A6 (uuid), INDEX IDX_8E05C933C3423909 (driver_id), INDEX IDX_8E05C9336E59D40D (race_id), INDEX IDX_8E05C9334EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE duel (id INT AUTO_INCREMENT NOT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, player_driver1_id INT DEFAULT NULL, player_driver2_id INT DEFAULT NULL, race_id INT DEFAULT NULL, season_id INT DEFAULT NULL, position VARCHAR(3) NOT NULL, score INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_9BB4A762D17F50A6 (uuid), INDEX IDX_9BB4A762C0990423 (player1_id), INDEX IDX_9BB4A762D22CABCD (player2_id), INDEX IDX_9BB4A762F0D65333 (player_driver1_id), INDEX IDX_9BB4A762E263FCDD (player_driver2_id), INDEX IDX_9BB4A7626E59D40D (race_id), INDEX IDX_9BB4A7624EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strategy (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, race_id INT DEFAULT NULL, season_id INT DEFAULT NULL, position VARCHAR(3) NOT NULL, score INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_144645EDD17F50A6 (uuid), INDEX IDX_144645ED99E6F5DF (player_id), INDEX IDX_144645EDC3423909 (driver_id), INDEX IDX_144645ED6E59D40D (race_id), INDEX IDX_144645ED4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_performance (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, race_id INT DEFAULT NULL, season_id INT DEFAULT NULL, multiplier INT NOT NULL, position VARCHAR(3) NOT NULL, score INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_69E779CCD17F50A6 (uuid), INDEX IDX_69E779CC296CD8AE (team_id), INDEX IDX_69E779CC6E59D40D (race_id), INDEX IDX_69E779CC4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver_performance ADD CONSTRAINT FK_8E05C933C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE driver_performance ADD CONSTRAINT FK_8E05C9336E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE driver_performance ADD CONSTRAINT FK_8E05C9334EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A762C0990423 FOREIGN KEY (player1_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A762D22CABCD FOREIGN KEY (player2_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A762F0D65333 FOREIGN KEY (player_driver1_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A762E263FCDD FOREIGN KEY (player_driver2_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A7626E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A7624EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645ED99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645EDC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645ED6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645ED4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE team_performance ADD CONSTRAINT FK_69E779CC296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team_performance ADD CONSTRAINT FK_69E779CC6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE team_performance ADD CONSTRAINT FK_69E779CC4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance DROP FOREIGN KEY FK_8E05C933C3423909');
        $this->addSql('ALTER TABLE driver_performance DROP FOREIGN KEY FK_8E05C9336E59D40D');
        $this->addSql('ALTER TABLE driver_performance DROP FOREIGN KEY FK_8E05C9334EC001D1');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A762C0990423');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A762D22CABCD');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A762F0D65333');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A762E263FCDD');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A7626E59D40D');
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A7624EC001D1');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645ED99E6F5DF');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645EDC3423909');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645ED6E59D40D');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645ED4EC001D1');
        $this->addSql('ALTER TABLE team_performance DROP FOREIGN KEY FK_69E779CC296CD8AE');
        $this->addSql('ALTER TABLE team_performance DROP FOREIGN KEY FK_69E779CC6E59D40D');
        $this->addSql('ALTER TABLE team_performance DROP FOREIGN KEY FK_69E779CC4EC001D1');
        $this->addSql('DROP TABLE driver_performance');
        $this->addSql('DROP TABLE duel');
        $this->addSql('DROP TABLE strategy');
        $this->addSql('DROP TABLE team_performance');
    }
}
