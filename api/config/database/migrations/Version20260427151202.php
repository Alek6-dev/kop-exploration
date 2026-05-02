<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427151202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE season_bonus_usage (id INT AUTO_INCREMENT NOT NULL, participation_id INT NOT NULL, gp_strategy_id INT DEFAULT NULL, bonus_type VARCHAR(255) NOT NULL, price_paid INT NOT NULL, used_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_4DD1D555D17F50A6 (uuid), INDEX IDX_4DD1D5556ACE3B73 (participation_id), INDEX IDX_4DD1D55572FD8D09 (gp_strategy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_gpstrategy (id INT AUTO_INCREMENT NOT NULL, participation_id INT NOT NULL, race_id INT NOT NULL, driver1_id INT NOT NULL, driver2_id INT NOT NULL, team_id INT NOT NULL, points INT DEFAULT NULL, locked TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_6148E389D17F50A6 (uuid), INDEX IDX_6148E3896ACE3B73 (participation_id), INDEX IDX_6148E3896E59D40D (race_id), INDEX IDX_6148E389AF73D70E (driver1_id), INDEX IDX_6148E389BDC678E0 (driver2_id), INDEX IDX_6148E389296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_participation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, season_id INT NOT NULL, total_points INT NOT NULL, wallet_balance INT NOT NULL, enrolled_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_C8571D32D17F50A6 (uuid), INDEX IDX_C8571D32A76ED395 (user_id), INDEX IDX_C8571D324EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_roster (id INT AUTO_INCREMENT NOT NULL, participation_id INT NOT NULL, budget_spent INT NOT NULL, validated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_76C2534BD17F50A6 (uuid), UNIQUE INDEX UNIQ_76C2534B6ACE3B73 (participation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_roster_driver (id INT AUTO_INCREMENT NOT NULL, roster_id INT NOT NULL, driver_id INT NOT NULL, purchase_price INT NOT NULL, max_usages INT NOT NULL, usages_left INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_F9C2847BD17F50A6 (uuid), INDEX IDX_F9C2847B75404483 (roster_id), INDEX IDX_F9C2847BC3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season_roster_team (id INT AUTO_INCREMENT NOT NULL, roster_id INT NOT NULL, team_id INT NOT NULL, purchase_price INT NOT NULL, max_usages INT NOT NULL, usages_left INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_8410834ED17F50A6 (uuid), INDEX IDX_8410834E75404483 (roster_id), INDEX IDX_8410834E296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE season_bonus_usage ADD CONSTRAINT FK_4DD1D5556ACE3B73 FOREIGN KEY (participation_id) REFERENCES season_participation (id)');
        $this->addSql('ALTER TABLE season_bonus_usage ADD CONSTRAINT FK_4DD1D55572FD8D09 FOREIGN KEY (gp_strategy_id) REFERENCES season_gpstrategy (id)');
        $this->addSql('ALTER TABLE season_gpstrategy ADD CONSTRAINT FK_6148E3896ACE3B73 FOREIGN KEY (participation_id) REFERENCES season_participation (id)');
        $this->addSql('ALTER TABLE season_gpstrategy ADD CONSTRAINT FK_6148E3896E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE season_gpstrategy ADD CONSTRAINT FK_6148E389AF73D70E FOREIGN KEY (driver1_id) REFERENCES season_roster_driver (id)');
        $this->addSql('ALTER TABLE season_gpstrategy ADD CONSTRAINT FK_6148E389BDC678E0 FOREIGN KEY (driver2_id) REFERENCES season_roster_driver (id)');
        $this->addSql('ALTER TABLE season_gpstrategy ADD CONSTRAINT FK_6148E389296CD8AE FOREIGN KEY (team_id) REFERENCES season_roster_team (id)');
        $this->addSql('ALTER TABLE season_participation ADD CONSTRAINT FK_C8571D32A76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id)');
        $this->addSql('ALTER TABLE season_participation ADD CONSTRAINT FK_C8571D324EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season_roster ADD CONSTRAINT FK_76C2534B6ACE3B73 FOREIGN KEY (participation_id) REFERENCES season_participation (id)');
        $this->addSql('ALTER TABLE season_roster_driver ADD CONSTRAINT FK_F9C2847B75404483 FOREIGN KEY (roster_id) REFERENCES season_roster (id)');
        $this->addSql('ALTER TABLE season_roster_driver ADD CONSTRAINT FK_F9C2847BC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE season_roster_team ADD CONSTRAINT FK_8410834E75404483 FOREIGN KEY (roster_id) REFERENCES season_roster (id)');
        $this->addSql('ALTER TABLE season_roster_team ADD CONSTRAINT FK_8410834E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE admin_credit_grant DROP FOREIGN KEY FK_acg_player');
        $this->addSql('ALTER TABLE admin_credit_grant DROP FOREIGN KEY FK_acg_championship');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players DROP FOREIGN KEY FK_excl_user');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players DROP FOREIGN KEY FK_excl_grant');
        $this->addSql('ALTER TABLE notification_read DROP FOREIGN KEY FK_206B0A5DEF1A9D84');
        $this->addSql('ALTER TABLE notification_read DROP FOREIGN KEY FK_206B0A5DA76ED395');
        $this->addSql('ALTER TABLE notification_targets DROP FOREIGN KEY FK_BA5D94A4EF1A9D84');
        $this->addSql('ALTER TABLE notification_targets DROP FOREIGN KEY FK_BA5D94A4A06C2881');
        $this->addSql('DROP TABLE admin_credit_grant');
        $this->addSql('DROP TABLE admin_credit_grant_excluded_players');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_read');
        $this->addSql('DROP TABLE notification_targets');
        $this->addSql('ALTER TABLE driver DROP is_archived');
        $this->addSql('ALTER TABLE race DROP is_archived');
        $this->addSql('ALTER TABLE result DROP is_archived');
        $this->addSql('ALTER TABLE season DROP is_archived');
        $this->addSql('ALTER TABLE team DROP is_archived');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_credit_grant (id INT AUTO_INCREMENT NOT NULL, target_player_id INT DEFAULT NULL, target_championship_id INT DEFAULT NULL, amount INT NOT NULL, is_deduction TINYINT(1) DEFAULT 0 NOT NULL, reason VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, target_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_admin_credit_grant_uuid (uuid), INDEX IDX_admin_credit_grant_player (target_player_id), INDEX IDX_admin_credit_grant_championship (target_championship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_credit_grant_excluded_players (admin_credit_grant_id INT NOT NULL, user_visitor_id INT NOT NULL, INDEX IDX_excl_grant (admin_credit_grant_id), INDEX IDX_excl_user (user_visitor_id), PRIMARY KEY(admin_credit_grant_id, user_visitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_for_all TINYINT(1) DEFAULT 0 NOT NULL, scheduled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', show_as_popup TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_BF5476CAD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notification_read (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, notification_id INT NOT NULL, read_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_206B0A5DD17F50A6 (uuid), INDEX IDX_206B0A5DA76ED395 (user_id), INDEX IDX_206B0A5DEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE notification_targets (notification_id INT NOT NULL, user_visitor_id INT NOT NULL, INDEX IDX_BA5D94A4EF1A9D84 (notification_id), INDEX IDX_BA5D94A4A06C2881 (user_visitor_id), PRIMARY KEY(notification_id, user_visitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE admin_credit_grant ADD CONSTRAINT FK_acg_player FOREIGN KEY (target_player_id) REFERENCES user_visitor (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE admin_credit_grant ADD CONSTRAINT FK_acg_championship FOREIGN KEY (target_championship_id) REFERENCES championship (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players ADD CONSTRAINT FK_excl_user FOREIGN KEY (user_visitor_id) REFERENCES user_visitor (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players ADD CONSTRAINT FK_excl_grant FOREIGN KEY (admin_credit_grant_id) REFERENCES admin_credit_grant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_read ADD CONSTRAINT FK_206B0A5DEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_read ADD CONSTRAINT FK_206B0A5DA76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_targets ADD CONSTRAINT FK_BA5D94A4EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_targets ADD CONSTRAINT FK_BA5D94A4A06C2881 FOREIGN KEY (user_visitor_id) REFERENCES user_visitor (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE season_bonus_usage DROP FOREIGN KEY FK_4DD1D5556ACE3B73');
        $this->addSql('ALTER TABLE season_bonus_usage DROP FOREIGN KEY FK_4DD1D55572FD8D09');
        $this->addSql('ALTER TABLE season_gpstrategy DROP FOREIGN KEY FK_6148E3896ACE3B73');
        $this->addSql('ALTER TABLE season_gpstrategy DROP FOREIGN KEY FK_6148E3896E59D40D');
        $this->addSql('ALTER TABLE season_gpstrategy DROP FOREIGN KEY FK_6148E389AF73D70E');
        $this->addSql('ALTER TABLE season_gpstrategy DROP FOREIGN KEY FK_6148E389BDC678E0');
        $this->addSql('ALTER TABLE season_gpstrategy DROP FOREIGN KEY FK_6148E389296CD8AE');
        $this->addSql('ALTER TABLE season_participation DROP FOREIGN KEY FK_C8571D32A76ED395');
        $this->addSql('ALTER TABLE season_participation DROP FOREIGN KEY FK_C8571D324EC001D1');
        $this->addSql('ALTER TABLE season_roster DROP FOREIGN KEY FK_76C2534B6ACE3B73');
        $this->addSql('ALTER TABLE season_roster_driver DROP FOREIGN KEY FK_F9C2847B75404483');
        $this->addSql('ALTER TABLE season_roster_driver DROP FOREIGN KEY FK_F9C2847BC3423909');
        $this->addSql('ALTER TABLE season_roster_team DROP FOREIGN KEY FK_8410834E75404483');
        $this->addSql('ALTER TABLE season_roster_team DROP FOREIGN KEY FK_8410834E296CD8AE');
        $this->addSql('DROP TABLE season_bonus_usage');
        $this->addSql('DROP TABLE season_gpstrategy');
        $this->addSql('DROP TABLE season_participation');
        $this->addSql('DROP TABLE season_roster');
        $this->addSql('DROP TABLE season_roster_driver');
        $this->addSql('DROP TABLE season_roster_team');
        $this->addSql('ALTER TABLE driver ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE race ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE result ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE season ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE team ADD is_archived TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
