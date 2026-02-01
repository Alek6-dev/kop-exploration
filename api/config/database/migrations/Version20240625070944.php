<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625070944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add points on bonus_application and limit usage on bonus per target.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus ADD cumulative_times INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus_application ADD strategy_id INT DEFAULT NULL, ADD duel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56DD5CAD932 FOREIGN KEY (strategy_id) REFERENCES strategy (id)');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D58875E FOREIGN KEY (duel_id) REFERENCES duel (id)');
        $this->addSql('CREATE INDEX IDX_9E11E56DD5CAD932 ON bonus_application (strategy_id)');
        $this->addSql('CREATE INDEX IDX_9E11E56D58875E ON bonus_application (duel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus DROP cumulative_times, CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56DD5CAD932');
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D58875E');
        $this->addSql('DROP INDEX IDX_9E11E56DD5CAD932 ON bonus_application');
        $this->addSql('DROP INDEX IDX_9E11E56D58875E ON bonus_application');
        $this->addSql('ALTER TABLE bonus_application DROP strategy_id, DROP duel_id');
    }
}
