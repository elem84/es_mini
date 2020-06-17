<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200609100738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if (($fp = fopen(__DIR__ . "/directors.csv", "r")) !== FALSE)
        {
            while (($row = fgetcsv($fp, 1000, ",")) !== FALSE)
            {
                $this->addSql('INSERT INTO `directors` SET `id` = ?, `name` = ?', $row);
            }

            fclose($fp);
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
