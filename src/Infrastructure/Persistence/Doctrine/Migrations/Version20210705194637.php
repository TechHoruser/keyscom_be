<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705194637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE machines ADD project_uuid UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE machines ADD ip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE machines ADD domain VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE machines ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE machines ADD CONSTRAINT FK_F1CE8DEDE8EE98BE FOREIGN KEY (project_uuid) REFERENCES projects (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F1CE8DEDE8EE98BE ON machines (project_uuid)');
        $this->addSql('ALTER TABLE projects ADD client_uuid UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE projects ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4E393C4 FOREIGN KEY (client_uuid) REFERENCES clients (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5C93B3A4E393C4 ON projects (client_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE clients DROP name');
        $this->addSql('ALTER TABLE machines DROP CONSTRAINT FK_F1CE8DEDE8EE98BE');
        $this->addSql('DROP INDEX IDX_F1CE8DEDE8EE98BE');
        $this->addSql('ALTER TABLE machines DROP project_uuid');
        $this->addSql('ALTER TABLE machines DROP ip');
        $this->addSql('ALTER TABLE machines DROP domain');
        $this->addSql('ALTER TABLE machines DROP type');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A4E393C4');
        $this->addSql('DROP INDEX IDX_5C93B3A4E393C4');
        $this->addSql('ALTER TABLE projects DROP client_uuid');
        $this->addSql('ALTER TABLE projects DROP name');
        $this->addSql('ALTER TABLE projects DROP start_date');
        $this->addSql('ALTER TABLE projects DROP end_date');
    }
}
