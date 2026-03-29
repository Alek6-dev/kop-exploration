<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604130321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add wallet system';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit_pack (id INT AUTO_INCREMENT NOT NULL, product_id VARCHAR(255) NOT NULL, credit INT NOT NULL, price DOUBLE PRECISION NOT NULL, message VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_901CE11E4584665A (product_id), UNIQUE INDEX UNIQ_901CE11ED17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_wallet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, credit INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_4D5E737ED17F50A6 (uuid), UNIQUE INDEX UNIQ_4D5E737EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_wallet_transaction (id INT AUTO_INCREMENT NOT NULL, credit_wallet_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, operator VARCHAR(255) NOT NULL, balance_before INT NOT NULL, balance_after INT NOT NULL, external_payment_id VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_E31839CD17F50A6 (uuid), INDEX IDX_E31839CB0EFD6E1 (credit_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit_wallet ADD CONSTRAINT FK_4D5E737EA76ED395 FOREIGN KEY (user_id) REFERENCES user_visitor (id)');
        $this->addSql('ALTER TABLE credit_wallet_transaction ADD CONSTRAINT FK_E31839CB0EFD6E1 FOREIGN KEY (credit_wallet_id) REFERENCES credit_wallet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit_wallet DROP FOREIGN KEY FK_4D5E737EA76ED395');
        $this->addSql('ALTER TABLE credit_wallet_transaction DROP FOREIGN KEY FK_E31839CB0EFD6E1');
        $this->addSql('DROP TABLE credit_pack');
        $this->addSql('DROP TABLE credit_wallet');
        $this->addSql('DROP TABLE credit_wallet_transaction');
    }
}
