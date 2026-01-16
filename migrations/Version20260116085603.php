<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116085603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bar (id INT AUTO_INCREMENT NOT NULL, sim_id INT NOT NULL, time INT NOT NULL, produced_egg INT NOT NULL, produced_meat INT NOT NULL, living_egg INT NOT NULL, living_chick_female INT NOT NULL, living_chick_male INT NOT NULL, living_hen_virgo INT NOT NULL, living_hen_fertilized INT NOT NULL, living_rooster INT NOT NULL, INDEX IDX_76FF8CAAF81AF80C (sim_id), UNIQUE INDEX UNIQ_76FF8CAABF3967506F949845 (id, time), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sim (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, date DATETIME NOT NULL, duration DOUBLE PRECISION NOT NULL, initial_hen INT NOT NULL, initial_rooster INT NOT NULL, limit_hen INT NOT NULL, limit_rooster INT NOT NULL, limit_nest INT NOT NULL, time INT NOT NULL, seed BIGINT NOT NULL, INDEX IDX_2ECAC2108925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE world (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, egg_to_female DOUBLE PRECISION NOT NULL, replace_mode VARCHAR(255) NOT NULL, egg_to_chick_min INT NOT NULL, egg_to_chick_max INT NOT NULL, chick_to_chicken_min INT NOT NULL, chick_to_chicken_max INT NOT NULL, hen_to_lay_min INT NOT NULL, hen_to_lay_max INT NOT NULL, rooster_to_fertilize_min INT NOT NULL, rooster_to_fertilize_max INT NOT NULL, UNIQUE INDEX UNIQ_3A771143EA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bar ADD CONSTRAINT FK_76FF8CAAF81AF80C FOREIGN KEY (sim_id) REFERENCES sim (id)');
        $this->addSql('ALTER TABLE sim ADD CONSTRAINT FK_2ECAC2108925311C FOREIGN KEY (world_id) REFERENCES world (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bar DROP FOREIGN KEY FK_76FF8CAAF81AF80C');
        $this->addSql('ALTER TABLE sim DROP FOREIGN KEY FK_2ECAC2108925311C');
        $this->addSql('DROP TABLE bar');
        $this->addSql('DROP TABLE sim');
        $this->addSql('DROP TABLE world');
    }
}
