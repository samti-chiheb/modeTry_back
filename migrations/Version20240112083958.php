<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112083958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tryon_result (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_C1053C96A76ED395 (user_id), INDEX IDX_C1053C967E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tryon_result_clothing_items (tryon_result_id INT NOT NULL, clothing_items_id INT NOT NULL, INDEX IDX_617C610F9C468CC3 (tryon_result_id), INDEX IDX_617C610FE16D1B8F (clothing_items_id), PRIMARY KEY(tryon_result_id, clothing_items_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_model (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_35578981A76ED395 (user_id), INDEX IDX_355789817E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tryon_result ADD CONSTRAINT FK_C1053C96A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tryon_result ADD CONSTRAINT FK_C1053C967E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE tryon_result_clothing_items ADD CONSTRAINT FK_617C610F9C468CC3 FOREIGN KEY (tryon_result_id) REFERENCES tryon_result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tryon_result_clothing_items ADD CONSTRAINT FK_617C610FE16D1B8F FOREIGN KEY (clothing_items_id) REFERENCES clothing_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_model ADD CONSTRAINT FK_35578981A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_model ADD CONSTRAINT FK_355789817E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tryon_result DROP FOREIGN KEY FK_C1053C96A76ED395');
        $this->addSql('ALTER TABLE tryon_result DROP FOREIGN KEY FK_C1053C967E9E4C8C');
        $this->addSql('ALTER TABLE tryon_result_clothing_items DROP FOREIGN KEY FK_617C610F9C468CC3');
        $this->addSql('ALTER TABLE tryon_result_clothing_items DROP FOREIGN KEY FK_617C610FE16D1B8F');
        $this->addSql('ALTER TABLE user_model DROP FOREIGN KEY FK_35578981A76ED395');
        $this->addSql('ALTER TABLE user_model DROP FOREIGN KEY FK_355789817E9E4C8C');
        $this->addSql('DROP TABLE tryon_result');
        $this->addSql('DROP TABLE tryon_result_clothing_items');
        $this->addSql('DROP TABLE user_model');
    }
}
