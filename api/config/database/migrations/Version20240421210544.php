<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421210544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE duel_driver_performance (id INT AUTO_INCREMENT NOT NULL, duel_id INT DEFAULT NULL, performance_reference_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, qualification_points INT NOT NULL, race_points INT NOT NULL, sprint_points INT NOT NULL, position_gain INT NOT NULL, position INT DEFAULT NULL, points VARCHAR(3) DEFAULT NULL, score INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_338FF992D17F50A6 (uuid), INDEX IDX_338FF99258875E (duel_id), INDEX IDX_338FF9927694436A (performance_reference_id), INDEX IDX_338FF992C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE duel_team_performance (id INT AUTO_INCREMENT NOT NULL, duel_id INT DEFAULT NULL, performance_reference_id INT DEFAULT NULL, team_id INT DEFAULT NULL, multiplier INT NOT NULL, position INT DEFAULT NULL, points VARCHAR(3) DEFAULT NULL, score INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_73617927D17F50A6 (uuid), UNIQUE INDEX UNIQ_7361792758875E (duel_id), INDEX IDX_736179277694436A (performance_reference_id), INDEX IDX_73617927296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strategy_driver_performance (id INT AUTO_INCREMENT NOT NULL, strategy_id INT DEFAULT NULL, performance_reference_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, qualification_points INT NOT NULL, race_points INT NOT NULL, sprint_points INT NOT NULL, position_gain INT NOT NULL, position INT DEFAULT NULL, points VARCHAR(3) DEFAULT NULL, score INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_7E66EDCCD17F50A6 (uuid), INDEX IDX_7E66EDCCD5CAD932 (strategy_id), INDEX IDX_7E66EDCC7694436A (performance_reference_id), INDEX IDX_7E66EDCCC3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strategy_team_performance (id INT AUTO_INCREMENT NOT NULL, strategy_id INT DEFAULT NULL, performance_reference_id INT DEFAULT NULL, team_id INT DEFAULT NULL, multiplier INT NOT NULL, position INT DEFAULT NULL, points VARCHAR(3) DEFAULT NULL, score INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_D8C90498D17F50A6 (uuid), UNIQUE INDEX UNIQ_D8C90498D5CAD932 (strategy_id), INDEX IDX_D8C904987694436A (performance_reference_id), INDEX IDX_D8C90498296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE duel_driver_performance ADD CONSTRAINT FK_338FF99258875E FOREIGN KEY (duel_id) REFERENCES duel (id)');
        $this->addSql('ALTER TABLE duel_driver_performance ADD CONSTRAINT FK_338FF9927694436A FOREIGN KEY (performance_reference_id) REFERENCES driver_performance (id)');
        $this->addSql('ALTER TABLE duel_driver_performance ADD CONSTRAINT FK_338FF992C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_7361792758875E FOREIGN KEY (duel_id) REFERENCES duel (id)');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_736179277694436A FOREIGN KEY (performance_reference_id) REFERENCES team_performance (id)');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_73617927296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD CONSTRAINT FK_7E66EDCCD5CAD932 FOREIGN KEY (strategy_id) REFERENCES strategy (id)');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD CONSTRAINT FK_7E66EDCC7694436A FOREIGN KEY (performance_reference_id) REFERENCES driver_performance (id)');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD CONSTRAINT FK_7E66EDCCC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE strategy_team_performance ADD CONSTRAINT FK_D8C90498D5CAD932 FOREIGN KEY (strategy_id) REFERENCES strategy (id)');
        $this->addSql('ALTER TABLE strategy_team_performance ADD CONSTRAINT FK_D8C904987694436A FOREIGN KEY (performance_reference_id) REFERENCES team_performance (id)');
        $this->addSql('ALTER TABLE strategy_team_performance ADD CONSTRAINT FK_D8C90498296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE driver_performance ADD result_id INT DEFAULT NULL, CHANGE position position INT DEFAULT NULL, CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE driver_performance ADD CONSTRAINT FK_8E05C9337A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8E05C9337A7B643 ON driver_performance (result_id)');
        $this->addSql('ALTER TABLE duel CHANGE position position INT DEFAULT NULL, CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD remaining_duel_usage_driver1 INT NOT NULL, ADD remaining_duel_usage_driver2 INT NOT NULL, CHANGE points points VARCHAR(3) DEFAULT NULL, CHANGE position position INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy CHANGE position position INT DEFAULT NULL, CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance ADD result_id INT DEFAULT NULL, CHANGE position position INT DEFAULT NULL, CHANGE points points VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance ADD CONSTRAINT FK_69E779CC7A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_69E779CC7A7B643 ON team_performance (result_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel_driver_performance DROP FOREIGN KEY FK_338FF99258875E');
        $this->addSql('ALTER TABLE duel_driver_performance DROP FOREIGN KEY FK_338FF9927694436A');
        $this->addSql('ALTER TABLE duel_driver_performance DROP FOREIGN KEY FK_338FF992C3423909');
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_7361792758875E');
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_736179277694436A');
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_73617927296CD8AE');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP FOREIGN KEY FK_7E66EDCCD5CAD932');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP FOREIGN KEY FK_7E66EDCC7694436A');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP FOREIGN KEY FK_7E66EDCCC3423909');
        $this->addSql('ALTER TABLE strategy_team_performance DROP FOREIGN KEY FK_D8C90498D5CAD932');
        $this->addSql('ALTER TABLE strategy_team_performance DROP FOREIGN KEY FK_D8C904987694436A');
        $this->addSql('ALTER TABLE strategy_team_performance DROP FOREIGN KEY FK_D8C90498296CD8AE');
        $this->addSql('DROP TABLE duel_driver_performance');
        $this->addSql('DROP TABLE duel_team_performance');
        $this->addSql('DROP TABLE strategy_driver_performance');
        $this->addSql('DROP TABLE strategy_team_performance');
        $this->addSql('ALTER TABLE driver_performance DROP FOREIGN KEY FK_8E05C9337A7B643');
        $this->addSql('DROP INDEX IDX_8E05C9337A7B643 ON driver_performance');
        $this->addSql('ALTER TABLE driver_performance DROP result_id, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player DROP remaining_duel_usage_driver1, DROP remaining_duel_usage_driver2, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance DROP FOREIGN KEY FK_69E779CC7A7B643');
        $this->addSql('DROP INDEX IDX_69E779CC7A7B643 ON team_performance');
        $this->addSql('ALTER TABLE team_performance DROP result_id, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
    }
}
