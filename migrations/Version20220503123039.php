<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503123039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_7BA2F5EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category CHANGE type type VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE lodging CHANGE name name VARCHAR(50) NOT NULL, CHANGE description description TINYTEXT NOT NULL, CHANGE address address TINYTEXT NOT NULL');
        $this->addSql('ALTER TABLE lodging_value CHANGE value value VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE new_field new_field VARCHAR(30) NOT NULL, CHANGE default_value default_value VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE api_token');
        $this->addSql('ALTER TABLE category CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lodging CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE address address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lodging_value CHANGE value value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE property CHANGE new_field new_field VARCHAR(255) NOT NULL, CHANGE default_value default_value VARCHAR(255) NOT NULL');
    }
}
