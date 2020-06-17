<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200604131334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE movies (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, title_org VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, rate NUMERIC(10, 1) NOT NULL, type VARCHAR(6) NOT NULL, poster VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies_genres (movies_id INT UNSIGNED NOT NULL, genres_id INT UNSIGNED NOT NULL, INDEX IDX_DF9737A253F590A4 (movies_id), INDEX IDX_DF9737A26A3B2603 (genres_id), PRIMARY KEY(movies_id, genres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies_directors (movies_id INT UNSIGNED NOT NULL, director_id INT UNSIGNED NOT NULL, INDEX IDX_8E4761F553F590A4 (movies_id), INDEX IDX_8E4761F5899FB366 (director_id), PRIMARY KEY(movies_id, director_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE directors (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movies_genres ADD CONSTRAINT FK_DF9737A253F590A4 FOREIGN KEY (movies_id) REFERENCES movies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movies_genres ADD CONSTRAINT FK_DF9737A26A3B2603 FOREIGN KEY (genres_id) REFERENCES genres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movies_directors ADD CONSTRAINT FK_8E4761F553F590A4 FOREIGN KEY (movies_id) REFERENCES movies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movies_directors ADD CONSTRAINT FK_8E4761F5899FB366 FOREIGN KEY (director_id) REFERENCES directors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movies_genres DROP FOREIGN KEY FK_DF9737A253F590A4');
        $this->addSql('ALTER TABLE movies_directors DROP FOREIGN KEY FK_8E4761F553F590A4');
        $this->addSql('ALTER TABLE movies_genres DROP FOREIGN KEY FK_DF9737A26A3B2603');
        $this->addSql('ALTER TABLE movies_directors DROP FOREIGN KEY FK_8E4761F5899FB366');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE movies_genres');
        $this->addSql('DROP TABLE movies_directors');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE directors');
    }
}
