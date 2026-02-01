<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703094339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus_application ADD race_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus_application ADD CONSTRAINT FK_9E11E56D6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('CREATE INDEX IDX_9E11E56D6E59D40D ON bonus_application (race_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus_application DROP FOREIGN KEY FK_9E11E56D6E59D40D');
        $this->addSql('DROP INDEX IDX_9E11E56D6E59D40D ON bonus_application');
        $this->addSql('ALTER TABLE bonus_application DROP race_id');
    }
}
