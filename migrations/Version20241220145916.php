<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220145916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE track_genre (track_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_F3A7915F5ED23C43 (track_id), INDEX IDX_F3A7915F4296D31F (genre_id), PRIMARY KEY(track_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE track_genre ADD CONSTRAINT FK_F3A7915F5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE track_genre ADD CONSTRAINT FK_F3A7915F4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_track DROP FOREIGN KEY FK_6BE5E7634296D31F');
        $this->addSql('ALTER TABLE genre_track DROP FOREIGN KEY FK_6BE5E7635ED23C43');
        $this->addSql('DROP TABLE genre_track');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre_track (genre_id INT NOT NULL, track_id INT NOT NULL, INDEX IDX_6BE5E7634296D31F (genre_id), INDEX IDX_6BE5E7635ED23C43 (track_id), PRIMARY KEY(genre_id, track_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE genre_track ADD CONSTRAINT FK_6BE5E7634296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_track ADD CONSTRAINT FK_6BE5E7635ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE track_genre DROP FOREIGN KEY FK_F3A7915F5ED23C43');
        $this->addSql('ALTER TABLE track_genre DROP FOREIGN KEY FK_F3A7915F4296D31F');
        $this->addSql('DROP TABLE track_genre');
    }
}
