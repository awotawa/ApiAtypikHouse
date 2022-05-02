<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502232348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lodging (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_8D35182A7E3C61F9 (owner_id), INDEX IDX_8D35182A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging_value (id INT AUTO_INCREMENT NOT NULL, lodging_id INT NOT NULL, property_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_4FF0CEFB87335AF1 (lodging_id), INDEX IDX_4FF0CEFB549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, lodging_id INT NOT NULL, type VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C87335AF1 (lodging_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owner (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_CF60E67CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, new_field VARCHAR(255) NOT NULL, default_value VARCHAR(255) NOT NULL, INDEX IDX_8BF21CDE12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lodging_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C8495587335AF1 (lodging_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE lodging_value ADD CONSTRAINT FK_4FF0CEFB87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE lodging_value ADD CONSTRAINT FK_4FF0CEFB549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
        $this->addSql('ALTER TABLE owner ADD CONSTRAINT FK_CF60E67CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495587335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodging_value DROP FOREIGN KEY FK_4FF0CEFB87335AF1');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C87335AF1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495587335AF1');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A7E3C61F9');
        $this->addSql('ALTER TABLE lodging_value DROP FOREIGN KEY FK_4FF0CEFB549213EC');
        $this->addSql('DROP TABLE lodging');
        $this->addSql('DROP TABLE lodging_value');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE owner');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE reservation');
    }
}
