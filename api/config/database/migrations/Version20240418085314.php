<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418085314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance ADD points INT DEFAULT NULL, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE score score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel ADD points INT DEFAULT NULL, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE score score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD position VARCHAR(3) DEFAULT NULL, ADD score INT DEFAULT NULL, CHANGE ranking_place points INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113296CD8AE');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113C3423909');
        $this->addSql('DROP INDEX IDX_136AC113296CD8AE ON result');
        $this->addSql('DROP INDEX IDX_136AC113C3423909 ON result');
        $this->addSql('ALTER TABLE result DROP driver_id, DROP team_id, DROP type');
        $this->addSql('ALTER TABLE result_lap ADD driver_id INT DEFAULT NULL, ADD team_id INT DEFAULT NULL, ADD type INT NOT NULL');
        $this->addSql('ALTER TABLE result_lap ADD CONSTRAINT FK_16691F83C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result_lap ADD CONSTRAINT FK_16691F83296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_16691F83C3423909 ON result_lap (driver_id)');
        $this->addSql('CREATE INDEX IDX_16691F83296CD8AE ON result_lap (team_id)');
        $this->addSql('ALTER TABLE strategy ADD points INT DEFAULT NULL, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE score score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_performance ADD points INT DEFAULT NULL, CHANGE position position VARCHAR(3) DEFAULT NULL, CHANGE score score INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance DROP points, CHANGE position position VARCHAR(3) NOT NULL, CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE duel DROP points, CHANGE position position VARCHAR(3) NOT NULL, CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD ranking_place INT DEFAULT NULL, DROP position, DROP points, DROP score');
        $this->addSql('ALTER TABLE result ADD driver_id INT DEFAULT NULL, ADD team_id INT DEFAULT NULL, ADD type INT NOT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_136AC113296CD8AE ON result (team_id)');
        $this->addSql('CREATE INDEX IDX_136AC113C3423909 ON result (driver_id)');
        $this->addSql('ALTER TABLE result_lap DROP FOREIGN KEY FK_16691F83C3423909');
        $this->addSql('ALTER TABLE result_lap DROP FOREIGN KEY FK_16691F83296CD8AE');
        $this->addSql('DROP INDEX IDX_16691F83C3423909 ON result_lap');
        $this->addSql('DROP INDEX IDX_16691F83296CD8AE ON result_lap');
        $this->addSql('ALTER TABLE result_lap DROP driver_id, DROP team_id, DROP type');
        $this->addSql('ALTER TABLE strategy DROP points, CHANGE position position VARCHAR(3) NOT NULL, CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE team_performance DROP points, CHANGE position position VARCHAR(3) NOT NULL, CHANGE score score INT NOT NULL');
    }
}
