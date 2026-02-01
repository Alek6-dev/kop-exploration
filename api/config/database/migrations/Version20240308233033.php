<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308233033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE parameter');

        $this->addSql('ALTER TABLE championship ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EBADDE6AD17F50A6 ON championship (uuid)');
        $this->addSql('ALTER TABLE championship_race ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE698EE6D17F50A6 ON championship_race (uuid)');
        $this->addSql('ALTER TABLE cosmetic ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9528AF1FD17F50A6 ON cosmetic (uuid)');
        $this->addSql('ALTER TABLE driver ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9D17F50A6 ON driver (uuid)');
        $this->addSql('ALTER TABLE parameter ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A979110D17F50A6 ON parameter (uuid)');
        $this->addSql('ALTER TABLE player ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65D17F50A6 ON player (uuid)');
        $this->addSql('ALTER TABLE race ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA6FBBAFD17F50A6 ON race (uuid)');
        $this->addSql('ALTER TABLE result ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_136AC113D17F50A6 ON result (uuid)');
        $this->addSql('ALTER TABLE result_lap ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16691F83D17F50A6 ON result_lap (uuid)');
        $this->addSql('ALTER TABLE season ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0E45BA9D17F50A6 ON season (uuid)');
        $this->addSql('ALTER TABLE season_race ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C262731D17F50A6 ON season_race (uuid)');
        $this->addSql('ALTER TABLE season_team ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42A93A81D17F50A6 ON season_team (uuid)');
        $this->addSql('ALTER TABLE team ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61FD17F50A6 ON team (uuid)');
        $this->addSql('ALTER TABLE user_admin ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6ACCF62ED17F50A6 ON user_admin (uuid)');
        $this->addSql('ALTER TABLE user_visitor ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92E8FF95D17F50A6 ON user_visitor (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EBADDE6AD17F50A6 ON championship');
        $this->addSql('ALTER TABLE championship DROP uuid');
        $this->addSql('DROP INDEX UNIQ_FE698EE6D17F50A6 ON championship_race');
        $this->addSql('ALTER TABLE championship_race DROP uuid');
        $this->addSql('DROP INDEX UNIQ_9528AF1FD17F50A6 ON cosmetic');
        $this->addSql('ALTER TABLE cosmetic DROP uuid');
        $this->addSql('DROP INDEX UNIQ_11667CD9D17F50A6 ON driver');
        $this->addSql('ALTER TABLE driver DROP uuid');
        $this->addSql('DROP INDEX UNIQ_2A979110D17F50A6 ON parameter');
        $this->addSql('ALTER TABLE parameter DROP uuid');
        $this->addSql('DROP INDEX UNIQ_98197A65D17F50A6 ON player');
        $this->addSql('ALTER TABLE player DROP uuid');
        $this->addSql('DROP INDEX UNIQ_DA6FBBAFD17F50A6 ON race');
        $this->addSql('ALTER TABLE race DROP uuid');
        $this->addSql('DROP INDEX UNIQ_136AC113D17F50A6 ON result');
        $this->addSql('ALTER TABLE result DROP uuid');
        $this->addSql('DROP INDEX UNIQ_16691F83D17F50A6 ON result_lap');
        $this->addSql('ALTER TABLE result_lap DROP uuid');
        $this->addSql('DROP INDEX UNIQ_F0E45BA9D17F50A6 ON season');
        $this->addSql('ALTER TABLE season DROP uuid');
        $this->addSql('DROP INDEX UNIQ_5C262731D17F50A6 ON season_race');
        $this->addSql('ALTER TABLE season_race DROP uuid');
        $this->addSql('DROP INDEX UNIQ_42A93A81D17F50A6 ON season_team');
        $this->addSql('ALTER TABLE season_team DROP uuid');
        $this->addSql('DROP INDEX UNIQ_C4E0A61FD17F50A6 ON team');
        $this->addSql('ALTER TABLE team DROP uuid');
        $this->addSql('DROP INDEX UNIQ_6ACCF62ED17F50A6 ON user_admin');
        $this->addSql('ALTER TABLE user_admin DROP uuid');
        $this->addSql('DROP INDEX UNIQ_92E8FF95D17F50A6 ON user_visitor');
        $this->addSql('ALTER TABLE user_visitor DROP uuid');
    }
}
