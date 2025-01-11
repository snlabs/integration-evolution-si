<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119224435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT NOT NULL, dep_election VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regional_advisory (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, adress_id INT DEFAULT NULL, civilite VARCHAR(5) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, groupe_politique VARCHAR(255) DEFAULT NULL, liste_electorale VARCHAR(255) DEFAULT NULL, fonction_executif VARCHAR(255) DEFAULT NULL, ville_naissance VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, mandature VARCHAR(255) DEFAULT NULL, executif TINYINT(1) DEFAULT NULL, commission_permanente TINYINT(1) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, date_debut_mandat DATE DEFAULT NULL, site_internet VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, blog VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, INDEX IDX_FA71346498260155 (region_id), UNIQUE INDEX UNIQ_FA7134648486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE regional_advisory ADD CONSTRAINT FK_FA71346498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE regional_advisory ADD CONSTRAINT FK_FA7134648486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regional_advisory DROP FOREIGN KEY FK_FA71346498260155');
        $this->addSql('ALTER TABLE regional_advisory DROP FOREIGN KEY FK_FA7134648486F9AC');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE regional_advisory');
    }
}
