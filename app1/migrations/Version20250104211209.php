<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104211209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT NOT NULL, dep_election VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regional_advisory (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, civilite VARCHAR(5) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, groupe_politique VARCHAR(255) DEFAULT NULL, fonction_executif VARCHAR(255) DEFAULT NULL, ville_naissance VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, profession VARCHAR(255) DEFAULT NULL, INDEX IDX_FA71346498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE regional_advisory ADD CONSTRAINT FK_FA71346498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regional_advisory DROP FOREIGN KEY FK_FA71346498260155');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE regional_advisory');
    }
}
