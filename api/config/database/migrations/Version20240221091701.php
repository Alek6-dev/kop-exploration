<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221091701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cosmetic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, type INT NOT NULL, color VARCHAR(255) NOT NULL, image1 VARCHAR(255) NOT NULL, image2 VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, replaced_driver_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, is_replacement TINYINT(1) DEFAULT 0 NOT NULL, replacement_date_start DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', replacement_date_end DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', image VARCHAR(255) NOT NULL, min_value INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_11667CD9296CD8AE (team_id), UNIQUE INDEX UNIQ_11667CD96B020215 (replaced_driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(7) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, team_id INT DEFAULT NULL, season_id INT DEFAULT NULL, race_id INT DEFAULT NULL, type INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_136AC113C3423909 (driver_id), INDEX IDX_136AC113296CD8AE (team_id), INDEX IDX_136AC1134EC001D1 (season_id), INDEX IDX_136AC1136E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result_lap (id INT AUTO_INCREMENT NOT NULL, result_id INT DEFAULT NULL, no_lap INT NOT NULL, place VARCHAR(3) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_16691F837A7B643 (result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_race (id INT AUTO_INCREMENT NOT NULL, race_id INT DEFAULT NULL, season_id INT DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', qualification_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', sprint_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', limit_strategy_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', laps INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5C2627316E59D40D (race_id), INDEX IDX_5C2627314EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_team (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, season_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_42A93A81296CD8AE (team_id), INDEX IDX_42A93A814EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, min_value INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_admin (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', password VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, reset_password_requested_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', creation_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6ACCF62EE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_visitor (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, email_validation_token VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', password VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, reset_password_requested_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', creation_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_92E8FF9586CC499D (pseudo), UNIQUE INDEX UNIQ_92E8FF95E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD96B020215 FOREIGN KEY (replaced_driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1134EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE result_lap ADD CONSTRAINT FK_16691F837A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE season_race ADD CONSTRAINT FK_5C2627316E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE season_race ADD CONSTRAINT FK_5C2627314EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season_team ADD CONSTRAINT FK_42A93A81296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE season_team ADD CONSTRAINT FK_42A93A814EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9296CD8AE');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD96B020215');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113C3423909');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113296CD8AE');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1134EC001D1');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1136E59D40D');
        $this->addSql('ALTER TABLE result_lap DROP FOREIGN KEY FK_16691F837A7B643');
        $this->addSql('ALTER TABLE season_race DROP FOREIGN KEY FK_5C2627316E59D40D');
        $this->addSql('ALTER TABLE season_race DROP FOREIGN KEY FK_5C2627314EC001D1');
        $this->addSql('ALTER TABLE season_team DROP FOREIGN KEY FK_42A93A81296CD8AE');
        $this->addSql('ALTER TABLE season_team DROP FOREIGN KEY FK_42A93A814EC001D1');
        $this->addSql('DROP TABLE cosmetic');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE result_lap');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE season_race');
        $this->addSql('DROP TABLE season_team');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user_admin');
        $this->addSql('DROP TABLE user_visitor');
    }
}
