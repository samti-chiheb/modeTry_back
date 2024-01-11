<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110073652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, photo_id INT NOT NULL, user_id INT NOT NULL, description VARCHAR(255) NOT NULL, tags VARCHAR(255) NOT NULL, visibility VARCHAR(255) NOT NULL, INDEX IDX_5A8A6C8D7E9E4C8C (photo_id), INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B5254C9A6739');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B5259D86650F');
        $this->addSql('DROP TABLE user_likes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_likes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, virtual_try_on_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AB08B5259D86650F (user_id_id), INDEX IDX_AB08B5254C9A6739 (virtual_try_on_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5254C9A6739 FOREIGN KEY (virtual_try_on_id_id) REFERENCES virtual_try_ons (id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5259D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D7E9E4C8C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP TABLE post');
    }
}
