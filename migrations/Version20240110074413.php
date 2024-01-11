<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110074413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_B48C75B2A76ED395 (user_id), INDEX IDX_B48C75B27E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_post ADD CONSTRAINT FK_B48C75B2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite_post ADD CONSTRAINT FK_B48C75B27E9E4C8C FOREIGN KEY (photo_id) REFERENCES user_photos (id)');
        $this->addSql('ALTER TABLE favorite_photos DROP FOREIGN KEY FK_34C38C23C51599E0');
        $this->addSql('ALTER TABLE favorite_photos DROP FOREIGN KEY FK_34C38C239D86650F');
        $this->addSql('DROP TABLE favorite_photos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_photos (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, photo_id_id INT NOT NULL, INDEX IDX_34C38C239D86650F (user_id_id), INDEX IDX_34C38C23C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite_photos ADD CONSTRAINT FK_34C38C23C51599E0 FOREIGN KEY (photo_id_id) REFERENCES user_photos (id)');
        $this->addSql('ALTER TABLE favorite_photos ADD CONSTRAINT FK_34C38C239D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite_post DROP FOREIGN KEY FK_B48C75B2A76ED395');
        $this->addSql('ALTER TABLE favorite_post DROP FOREIGN KEY FK_B48C75B27E9E4C8C');
        $this->addSql('DROP TABLE favorite_post');
    }
}
