<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620094700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add 5 bonus data';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO bonus (uuid, name, description, example, icon, price, type, target_type, sub_target_type, attribute, operation, value, is_joker, is_enabled, sort) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Undercut', 'Diminue de 10 points la Performance Pilote du pilote réserviste de l\'équipe de ton choix.', 'Le pilote réserviste d\'un adversaire marque 50 points de PP. Suite à ce bonus, son score est de 40 points.', '', 10, 'strategy', 'player', 'driver2', 'driver_score', '-', 10, false, true, 50)", $uuid));

        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO bonus (uuid, name, description, example, icon, price, type, target_type, sub_target_type, attribute, operation, value, is_joker, is_enabled, sort) VALUES (UNHEX(REPLACE('%s', '-', '')), 'DRS', 'Augmente de 10 points la Performance Pilote de ton pilote réserviste.', 'Ton pilote réserviste marque 50 points de PP. Suite à ce bonus, son score est de 60 points.', '', 15, 'strategy', 'self', 'driver2', 'driver_score', '+', 10, false, true, 50)", $uuid));

        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO bonus (uuid, name, description, example, icon, price, type, target_type, sub_target_type, attribute, operation, value, is_joker, is_enabled, sort) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Pilote du jour', 'Augmenter de 10 points la performance de ton pilote titulaire.', 'Ton pilote titulaire marque 30 points. Avec ce bonus il en marquera 40.', '', 10, 'strategy', 'self', 'driver1', 'driver_score', '+', 10, false, true, 50)", $uuid));

        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO bonus (uuid, name, description, example, icon, price, type, target_type, sub_target_type, attribute, operation, value, is_joker, is_enabled, sort) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Pénalité', 'Diminue de 0,5 points le Multiplicateur d\'Écurie de l\'équipe de ton choix.', 'Une équipe possède un ME de 1,7. Suite à ce bonus, son ME est de 1,2.', '', 18, 'strategy', 'player', 'team', 'team_multiplier', '-', 5, false, true, 50)", $uuid));

        $uuid = (string) new UuidV4();
        $this->addSql(sprintf("INSERT INTO bonus (uuid, name, description, example, icon, price, type, target_type, sub_target_type, attribute, operation, value, is_joker, is_enabled, sort) VALUES (UNHEX(REPLACE('%s', '-', '')), 'Crevaison', 'Divise par 2 le score de Performance Pilote de ton adversaire lors du duel.', 'Le pilote adverse marque 50 points de PP. Suite à ce bonus, son score est de 25 points pour le duel.', '', 18, 'duel', 'player', 'driver1', 'driver_score', '/', 2, false, true, 50)", $uuid));
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
