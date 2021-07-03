<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531041833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_C82E748B8E8428 ON clients (created_at)');
        $this->addSql('CREATE INDEX IDX_C82E7443625D9F ON clients (updated_at)');
        $this->addSql('CREATE TABLE machines (uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_F1CE8DED8B8E8428 ON machines (created_at)');
        $this->addSql('CREATE INDEX IDX_F1CE8DED43625D9F ON machines (updated_at)');
        $this->addSql('CREATE TABLE projects (uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_5C93B3A48B8E8428 ON projects (created_at)');
        $this->addSql('CREATE INDEX IDX_5C93B3A443625D9F ON projects (updated_at)');
        $this->addSql('CREATE TABLE tenant_infrastructure_configuration (host VARCHAR(100) NOT NULL, user_db VARCHAR(100) NOT NULL, password_db VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(host))');
        $this->addSql('CREATE TABLE tenants (host VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(host))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE machines');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE tenant_infrastructure_configuration');
        $this->addSql('DROP TABLE tenants');
    }
}
