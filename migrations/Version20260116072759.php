<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116072759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bar (time INT NOT NULL, run_id INT NOT NULL, produced_egg INT NOT NULL, produced_meat INT NOT NULL, living_egg INT NOT NULL, living_chick_female INT NOT NULL, living_chick_male INT NOT NULL, living_hen_virgo INT NOT NULL, living_hen_fertilized INT NOT NULL, living_rooster INT NOT NULL, INDEX IDX_76FF8CAA84E3FEC4 (run_id), PRIMARY KEY(time)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bar ADD CONSTRAINT FK_76FF8CAA84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bar DROP FOREIGN KEY FK_76FF8CAA84E3FEC4');
        $this->addSql('DROP TABLE bar');
    }
}
