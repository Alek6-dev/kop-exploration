<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260403140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add admin_credit_grant and admin_credit_grant_excluded_players tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE admin_credit_grant (id INT AUTO_INCREMENT NOT NULL, target_player_id INT DEFAULT NULL, target_championship_id INT DEFAULT NULL, amount INT NOT NULL, is_deduction TINYINT(1) DEFAULT 0 NOT NULL, reason VARCHAR(255) NOT NULL, target_type VARCHAR(255) NOT NULL, executed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_admin_credit_grant_uuid (uuid), INDEX IDX_admin_credit_grant_player (target_player_id), INDEX IDX_admin_credit_grant_championship (target_championship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_credit_grant_excluded_players (admin_credit_grant_id INT NOT NULL, user_visitor_id INT NOT NULL, INDEX IDX_excl_grant (admin_credit_grant_id), INDEX IDX_excl_user (user_visitor_id), PRIMARY KEY(admin_credit_grant_id, user_visitor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_credit_grant ADD CONSTRAINT FK_acg_player FOREIGN KEY (target_player_id) REFERENCES user_visitor (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE admin_credit_grant ADD CONSTRAINT FK_acg_championship FOREIGN KEY (target_championship_id) REFERENCES championship (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players ADD CONSTRAINT FK_excl_grant FOREIGN KEY (admin_credit_grant_id) REFERENCES admin_credit_grant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players ADD CONSTRAINT FK_excl_user FOREIGN KEY (user_visitor_id) REFERENCES user_visitor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players DROP FOREIGN KEY FK_excl_grant');
        $this->addSql('ALTER TABLE admin_credit_grant_excluded_players DROP FOREIGN KEY FK_excl_user');
        $this->addSql('ALTER TABLE admin_credit_grant DROP FOREIGN KEY FK_acg_player');
        $this->addSql('ALTER TABLE admin_credit_grant DROP FOREIGN KEY FK_acg_championship');
        $this->addSql('DROP TABLE admin_credit_grant_excluded_players');
        $this->addSql('DROP TABLE admin_credit_grant');
    }
}
