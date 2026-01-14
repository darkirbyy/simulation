<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114131743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE run (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, date DATETIME NOT NULL, duration INT NOT NULL, initial_hen INT NOT NULL, initial_rooster INT NOT NULL, limit_hen INT NOT NULL, limit_rooster INT NOT NULL, limit_nest INT NOT NULL, time INT NOT NULL, seed INT NOT NULL, INDEX IDX_5076A4C08925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C08925311C FOREIGN KEY (world_id) REFERENCES world (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C08925311C');
        $this->addSql('DROP TABLE run');
    }
}
