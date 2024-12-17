<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217100235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD user_id INT DEFAULT NULL, ADD track_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C5ED23C43 ON comment (track_id)');
        $this->addSql('ALTER TABLE track ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D6E3F8A6A76ED395 ON track (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5ED23C43');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C5ED23C43 ON comment');
        $this->addSql('ALTER TABLE comment DROP user_id, DROP track_id');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6A76ED395');
        $this->addSql('DROP INDEX IDX_D6E3F8A6A76ED395 ON track');
        $this->addSql('ALTER TABLE track DROP user_id');
    }
}
