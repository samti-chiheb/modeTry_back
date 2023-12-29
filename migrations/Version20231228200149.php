<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231228200149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clothing_items (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_photos (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, photo_id_id INT NOT NULL, INDEX IDX_34C38C239D86650F (user_id_id), INDEX IDX_34C38C23C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_user_id_id INT NOT NULL, receiver_user_id_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB021E96C9023BC2 (sender_user_id_id), INDEX IDX_DB021E96DE2263C6 (receiver_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_comments (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, virtual_try_on_id_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BF13722A9D86650F (user_id_id), INDEX IDX_BF13722A4C9A6739 (virtual_try_on_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_likes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, virtual_try_on_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AB08B5259D86650F (user_id_id), INDEX IDX_AB08B5254C9A6739 (virtual_try_on_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_photos (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, image_path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6D24FBE49D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, height VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virtual_try_ons (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, clothing_item_id_id INT NOT NULL, photo_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E38323BB9D86650F (user_id_id), INDEX IDX_E38323BBD1C9192 (clothing_item_id_id), INDEX IDX_E38323BBC51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_photos ADD CONSTRAINT FK_34C38C239D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite_photos ADD CONSTRAINT FK_34C38C23C51599E0 FOREIGN KEY (photo_id_id) REFERENCES user_photos (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96C9023BC2 FOREIGN KEY (sender_user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96DE2263C6 FOREIGN KEY (receiver_user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_comments ADD CONSTRAINT FK_BF13722A9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_comments ADD CONSTRAINT FK_BF13722A4C9A6739 FOREIGN KEY (virtual_try_on_id_id) REFERENCES virtual_try_ons (id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5259D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5254C9A6739 FOREIGN KEY (virtual_try_on_id_id) REFERENCES virtual_try_ons (id)');
        $this->addSql('ALTER TABLE user_photos ADD CONSTRAINT FK_6D24FBE49D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE virtual_try_ons ADD CONSTRAINT FK_E38323BB9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE virtual_try_ons ADD CONSTRAINT FK_E38323BBD1C9192 FOREIGN KEY (clothing_item_id_id) REFERENCES clothing_items (id)');
        $this->addSql('ALTER TABLE virtual_try_ons ADD CONSTRAINT FK_E38323BBC51599E0 FOREIGN KEY (photo_id_id) REFERENCES user_photos (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_photos DROP FOREIGN KEY FK_34C38C239D86650F');
        $this->addSql('ALTER TABLE favorite_photos DROP FOREIGN KEY FK_34C38C23C51599E0');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96C9023BC2');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96DE2263C6');
        $this->addSql('ALTER TABLE user_comments DROP FOREIGN KEY FK_BF13722A9D86650F');
        $this->addSql('ALTER TABLE user_comments DROP FOREIGN KEY FK_BF13722A4C9A6739');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B5259D86650F');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B5254C9A6739');
        $this->addSql('ALTER TABLE user_photos DROP FOREIGN KEY FK_6D24FBE49D86650F');
        $this->addSql('ALTER TABLE virtual_try_ons DROP FOREIGN KEY FK_E38323BB9D86650F');
        $this->addSql('ALTER TABLE virtual_try_ons DROP FOREIGN KEY FK_E38323BBD1C9192');
        $this->addSql('ALTER TABLE virtual_try_ons DROP FOREIGN KEY FK_E38323BBC51599E0');
        $this->addSql('DROP TABLE clothing_items');
        $this->addSql('DROP TABLE favorite_photos');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE user_comments');
        $this->addSql('DROP TABLE user_likes');
        $this->addSql('DROP TABLE user_photos');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE virtual_try_ons');
    }
}
