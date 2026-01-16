<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116083328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bar DROP FOREIGN KEY FK_76FF8CAA84E3FEC4');
        $this->addSql('CREATE TABLE sim (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, date DATETIME NOT NULL, duration DOUBLE PRECISION NOT NULL, initial_hen INT NOT NULL, initial_rooster INT NOT NULL, limit_hen INT NOT NULL, limit_rooster INT NOT NULL, limit_nest INT NOT NULL, time INT NOT NULL, seed BIGINT NOT NULL, INDEX IDX_2ECAC2108925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sim ADD CONSTRAINT FK_2ECAC2108925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C08925311C');
        $this->addSql('DROP TABLE run');
        $this->addSql('DROP INDEX IDX_76FF8CAA84E3FEC4 ON bar');
        $this->addSql('ALTER TABLE bar CHANGE run_id sim_id INT NOT NULL');
        $this->addSql('ALTER TABLE bar ADD CONSTRAINT FK_76FF8CAAF81AF80C FOREIGN KEY (sim_id) REFERENCES sim (id)');
        $this->addSql('CREATE INDEX IDX_76FF8CAAF81AF80C ON bar (sim_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bar DROP FOREIGN KEY FK_76FF8CAAF81AF80C');
        $this->addSql('CREATE TABLE run (id INT AUTO_INCREMENT NOT NULL, world_id INT NOT NULL, date DATETIME NOT NULL, duration DOUBLE PRECISION NOT NULL, initial_hen INT NOT NULL, initial_rooster INT NOT NULL, limit_hen INT NOT NULL, limit_rooster INT NOT NULL, limit_nest INT NOT NULL, time INT NOT NULL, seed BIGINT NOT NULL, INDEX IDX_5076A4C08925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C08925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE sim DROP FOREIGN KEY FK_2ECAC2108925311C');
        $this->addSql('DROP TABLE sim');
        $this->addSql('DROP INDEX IDX_76FF8CAAF81AF80C ON bar');
        $this->addSql('ALTER TABLE bar CHANGE sim_id run_id INT NOT NULL');
        $this->addSql('ALTER TABLE bar ADD CONSTRAINT FK_76FF8CAA84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
        $this->addSql('CREATE INDEX IDX_76FF8CAA84E3FEC4 ON bar (run_id)');
    }
}
