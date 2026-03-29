<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240607100802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD96B020215');
        $this->addSql('DROP INDEX UNIQ_11667CD96B020215 ON driver');
        $this->addSql('ALTER TABLE driver CHANGE replaced_driver_id replaced_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD99AC69B54 FOREIGN KEY (replaced_by_id) REFERENCES driver (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD99AC69B54 ON driver (replaced_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD99AC69B54');
        $this->addSql('DROP INDEX UNIQ_11667CD99AC69B54 ON driver');
        $this->addSql('ALTER TABLE driver CHANGE replaced_by_id replaced_driver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD96B020215 FOREIGN KEY (replaced_driver_id) REFERENCES driver (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD96B020215 ON driver (replaced_driver_id)');
    }
}
