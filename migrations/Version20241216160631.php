<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216160631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_track (genre_id INT NOT NULL, track_id INT NOT NULL, INDEX IDX_6BE5E7634296D31F (genre_id), INDEX IDX_6BE5E7635ED23C43 (track_id), PRIMARY KEY(genre_id, track_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre_track ADD CONSTRAINT FK_6BE5E7634296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_track ADD CONSTRAINT FK_6BE5E7635ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre_track DROP FOREIGN KEY FK_6BE5E7634296D31F');
        $this->addSql('ALTER TABLE genre_track DROP FOREIGN KEY FK_6BE5E7635ED23C43');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_track');
    }
}
