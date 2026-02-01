<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515125906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_736179277694436A');
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_7361792758875E');
        $this->addSql('ALTER TABLE duel_team_performance DROP FOREIGN KEY FK_73617927296CD8AE');
        $this->addSql('DROP TABLE duel_team_performance');
        $this->addSql('ALTER TABLE duel CHANGE points_player1 points_player1 INT NOT NULL, CHANGE points_player2 points_player2 INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE duel_team_performance (id INT AUTO_INCREMENT NOT NULL, duel_id INT DEFAULT NULL, performance_reference_id INT DEFAULT NULL, team_id INT DEFAULT NULL, multiplier INT NOT NULL, position INT DEFAULT NULL, points VARCHAR(3) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, score INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_73617927D17F50A6 (uuid), INDEX IDX_736179277694436A (performance_reference_id), INDEX IDX_73617927296CD8AE (team_id), INDEX IDX_7361792758875E (duel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_736179277694436A FOREIGN KEY (performance_reference_id) REFERENCES team_performance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_7361792758875E FOREIGN KEY (duel_id) REFERENCES duel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE duel_team_performance ADD CONSTRAINT FK_73617927296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE duel CHANGE points_player1 points_player1 VARCHAR(3) DEFAULT NULL, CHANGE points_player2 points_player2 VARCHAR(3) DEFAULT NULL');
    }
}
