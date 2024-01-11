<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111082652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96C9023BC2');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96DE2263C6');
        $this->addSql('DROP INDEX IDX_DB021E96DE2263C6 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E96C9023BC2 ON messages');
        $this->addSql('ALTER TABLE messages ADD sender_user_id INT NOT NULL, ADD receiver_user_id INT NOT NULL, DROP sender_user_id_id, DROP receiver_user_id_id');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E962A98155E FOREIGN KEY (sender_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96DA57E237 FOREIGN KEY (receiver_user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E962A98155E ON messages (sender_user_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96DA57E237 ON messages (receiver_user_id)');
        $this->addSql('ALTER TABLE photo DROP name');
        $this->addSql('ALTER TABLE user_photos DROP FOREIGN KEY FK_6D24FBE49D86650F');
        $this->addSql('DROP INDEX IDX_6D24FBE49D86650F ON user_photos');
        $this->addSql('ALTER TABLE user_photos CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_photos ADD CONSTRAINT FK_6D24FBE4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6D24FBE4A76ED395 ON user_photos (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E962A98155E');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96DA57E237');
        $this->addSql('DROP INDEX IDX_DB021E962A98155E ON messages');
        $this->addSql('DROP INDEX IDX_DB021E96DA57E237 ON messages');
        $this->addSql('ALTER TABLE messages ADD sender_user_id_id INT NOT NULL, ADD receiver_user_id_id INT NOT NULL, DROP sender_user_id, DROP receiver_user_id');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96C9023BC2 FOREIGN KEY (sender_user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96DE2263C6 FOREIGN KEY (receiver_user_id_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DB021E96DE2263C6 ON messages (receiver_user_id_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96C9023BC2 ON messages (sender_user_id_id)');
        $this->addSql('ALTER TABLE photo ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_photos DROP FOREIGN KEY FK_6D24FBE4A76ED395');
        $this->addSql('DROP INDEX IDX_6D24FBE4A76ED395 ON user_photos');
        $this->addSql('ALTER TABLE user_photos CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_photos ADD CONSTRAINT FK_6D24FBE49D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6D24FBE49D86650F ON user_photos (user_id_id)');
    }
}
