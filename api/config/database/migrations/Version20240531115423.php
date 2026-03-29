<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531115423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance ADD sprint_position VARCHAR(2) DEFAULT NULL, ADD qualification_position VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE duel_driver_performance ADD sprint_position VARCHAR(2) DEFAULT NULL, ADD qualification_position VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP FOREIGN KEY FK_7E66EDCC7694436A');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD sprint_position VARCHAR(2) DEFAULT NULL, ADD qualification_position VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD CONSTRAINT FK_7E66EDCC7694436A FOREIGN KEY (performance_reference_id) REFERENCES driver_performance (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver_performance DROP sprint_position, DROP qualification_position');
        $this->addSql('ALTER TABLE duel_driver_performance DROP sprint_position, DROP qualification_position');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP FOREIGN KEY FK_7E66EDCC7694436A');
        $this->addSql('ALTER TABLE strategy_driver_performance DROP sprint_position, DROP qualification_position');
        $this->addSql('ALTER TABLE strategy_driver_performance ADD CONSTRAINT FK_7E66EDCC7694436A FOREIGN KEY (performance_reference_id) REFERENCES driver_performance (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
