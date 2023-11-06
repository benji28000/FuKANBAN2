<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106175756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projets (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateurs_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, date_modification DATE NOT NULL, CONSTRAINT FK_B454C1DB1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B454C1DB1E969C5 ON projets (utilisateurs_id)');
        $this->addSql('CREATE TABLE statut (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE tasks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, statut_id INTEGER DEFAULT NULL, project_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, date_creation DATE NOT NULL, date_modification DATE NOT NULL, CONSTRAINT FK_50586597F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_50586597166D1F9C FOREIGN KEY (project_id) REFERENCES projets (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_50586597F6203804 ON tasks (statut_id)');
        $this->addSql('CREATE INDEX IDX_50586597166D1F9C ON tasks (project_id)');
        $this->addSql('CREATE TABLE tasks_utilisateurs (tasks_id INTEGER NOT NULL, utilisateurs_id INTEGER NOT NULL, PRIMARY KEY(tasks_id, utilisateurs_id), CONSTRAINT FK_B1A67753E3272D31 FOREIGN KEY (tasks_id) REFERENCES tasks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B1A677531E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B1A67753E3272D31 ON tasks_utilisateurs (tasks_id)');
        $this->addSql('CREATE INDEX IDX_B1A677531E969C5 ON tasks_utilisateurs (utilisateurs_id)');
        $this->addSql('CREATE TABLE utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE projets');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE tasks_utilisateurs');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
