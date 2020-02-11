<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211082946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(125) DEFAULT NULL, zip_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organizer_id INT NOT NULL, site_id INT NOT NULL, location_id INT DEFAULT NULL, status_id INT NOT NULL, name VARCHAR(300) NOT NULL, starting_date_time DATETIME NOT NULL, duration INT NOT NULL, inscription_dead_line DATETIME NOT NULL, nb_max_registration INT NOT NULL, infos VARCHAR(500) DEFAULT NULL, INDEX IDX_3BAE0AA7876C4DDA (organizer_id), INDEX IDX_3BAE0AA7F6BD1646 (site_id), INDEX IDX_3BAE0AA764D218E (location_id), INDEX IDX_3BAE0AA76BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_member (event_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_427D8D2A71F7E88B (event_id), INDEX IDX_427D8D2A7597D3FE (member_id), PRIMARY KEY(event_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5E9E89CB8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, admin TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, password VARCHAR(1024) NOT NULL, INDEX IDX_70E4FA78F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, libel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA76BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_member ADD CONSTRAINT FK_427D8D2A7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8BAC62AF');
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A71F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE event_member DROP FOREIGN KEY FK_427D8D2A7597D3FE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F6BD1646');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78F6BD1646');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA76BF700BD');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_member');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE status');
    }
}
