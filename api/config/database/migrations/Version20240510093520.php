<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510093520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A7624EC001D1');
        $this->addSql('DROP INDEX IDX_9BB4A7624EC001D1 ON duel');
        $this->addSql('ALTER TABLE duel CHANGE season_id championship_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A76294DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('CREATE INDEX IDX_9BB4A76294DDBCE9 ON duel (championship_id)');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645ED4EC001D1');
        $this->addSql('DROP INDEX IDX_144645ED4EC001D1 ON strategy');
        $this->addSql('ALTER TABLE strategy CHANGE season_id championship_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645ED94DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('CREATE INDEX IDX_144645ED94DDBCE9 ON strategy (championship_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duel DROP FOREIGN KEY FK_9BB4A76294DDBCE9');
        $this->addSql('DROP INDEX IDX_9BB4A76294DDBCE9 ON duel');
        $this->addSql('ALTER TABLE duel CHANGE championship_id season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE duel ADD CONSTRAINT FK_9BB4A7624EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9BB4A7624EC001D1 ON duel (season_id)');
        $this->addSql('ALTER TABLE strategy DROP FOREIGN KEY FK_144645ED94DDBCE9');
        $this->addSql('DROP INDEX IDX_144645ED94DDBCE9 ON strategy');
        $this->addSql('ALTER TABLE strategy CHANGE championship_id season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE strategy ADD CONSTRAINT FK_144645ED4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_144645ED4EC001D1 ON strategy (season_id)');
    }
}
