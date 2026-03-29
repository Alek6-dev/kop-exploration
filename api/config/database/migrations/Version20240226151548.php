<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226151548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add championship, championship_race, player and parameter tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE championship (id INT AUTO_INCREMENT NOT NULL, season_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, joker_enabled TINYINT(1) NOT NULL, number_of_races INT NOT NULL, number_of_players INT NOT NULL, invitation_code VARCHAR(255) NOT NULL, status INT NOT NULL, registration_end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_EBADDE6ABA14FCCC (invitation_code), INDEX IDX_EBADDE6A4EC001D1 (season_id), INDEX IDX_EBADDE6AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE championship_race (id INT AUTO_INCREMENT NOT NULL, championship_id INT DEFAULT NULL, race_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FE698EE694DDBCE9 (championship_id), INDEX IDX_FE698EE66E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_2A97911077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, selected_team_id INT DEFAULT NULL, selected_driver1_id INT DEFAULT NULL, selected_driver2_id INT DEFAULT NULL, championship_id INT DEFAULT NULL, user_id INT DEFAULT NULL, remaining_budget INT NOT NULL, ranking_place INT DEFAULT NULL, remaining_usage_driver1 INT NOT NULL, remaining_usage_driver2 INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_98197A65C128A630 (selected_team_id), INDEX IDX_98197A6598F393ED (selected_driver1_id), INDEX IDX_98197A658A463C03 (selected_driver2_id), INDEX IDX_98197A6594DDBCE9 (championship_id), INDEX IDX_98197A65A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE championship ADD CONSTRAINT FK_EBADDE6A4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE championship ADD CONSTRAINT FK_EBADDE6AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user_visitor (id)');
        $this->addSql('ALTER TABLE championship_race ADD CONSTRAINT FK_FE698EE694DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE championship_race ADD CONSTRAINT FK_FE698EE66E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65C128A630 FOREIGN KEY (selected_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6598F393ED FOREIGN KEY (selected_driver1_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A658A463C03 FOREIGN KEY (selected_driver2_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6594DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id)');
        $this->addSql('ALTER TABLE season ADD is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE championship DROP FOREIGN KEY FK_EBADDE6A4EC001D1');
        $this->addSql('ALTER TABLE championship DROP FOREIGN KEY FK_EBADDE6AB03A8386');
        $this->addSql('ALTER TABLE championship_race DROP FOREIGN KEY FK_FE698EE694DDBCE9');
        $this->addSql('ALTER TABLE championship_race DROP FOREIGN KEY FK_FE698EE66E59D40D');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65C128A630');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6598F393ED');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A658A463C03');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6594DDBCE9');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65A76ED395');
        $this->addSql('DROP TABLE championship');
        $this->addSql('DROP TABLE championship_race');
        $this->addSql('DROP TABLE parameter');
        $this->addSql('DROP TABLE player');
        $this->addSql('ALTER TABLE season DROP is_active');
    }
}
