<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513143546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, content LONGTEXT NOT NULL, checked TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_owner (message_id INT NOT NULL, owner_id INT NOT NULL, INDEX IDX_30C61997537A1329 (message_id), INDEX IDX_30C619977E3C61F9 (owner_id), PRIMARY KEY(message_id, owner_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_owner ADD CONSTRAINT FK_30C61997537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message_owner ADD CONSTRAINT FK_30C619977E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property ADD value_type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_owner DROP FOREIGN KEY FK_30C61997537A1329');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_owner');
        $this->addSql('ALTER TABLE property DROP value_type');
    }
}
