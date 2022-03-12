<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220312182338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_user_on_machine (uuid UUID NOT NULL, permission_uuid UUID DEFAULT NULL, machine_uuid UUID DEFAULT NULL, action_to_do VARCHAR(255) NOT NULL, processed BOOLEAN NOT NULL, canceled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_97DD1CB680B1CB06 ON action_user_on_machine (permission_uuid)');
        $this->addSql('CREATE INDEX IDX_97DD1CB68775BBDF ON action_user_on_machine (machine_uuid)');
        $this->addSql('CREATE INDEX IDX_97DD1CB68B8E8428 ON action_user_on_machine (created_at)');
        $this->addSql('CREATE INDEX IDX_97DD1CB643625D9F ON action_user_on_machine (updated_at)');
        $this->addSql('CREATE INDEX IDX_97DD1CB6F587211C ON action_user_on_machine (action_to_do)');
        $this->addSql('CREATE INDEX IDX_97DD1CB627FB1B8B ON action_user_on_machine (processed)');
        $this->addSql('CREATE INDEX IDX_97DD1CB6E58B5301 ON action_user_on_machine (canceled)');
        $this->addSql('CREATE TABLE clients (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_C82E748B8E8428 ON clients (created_at)');
        $this->addSql('CREATE INDEX IDX_C82E7443625D9F ON clients (updated_at)');
        $this->addSql('CREATE TABLE machines (uuid UUID NOT NULL, project_uuid UUID DEFAULT NULL, ip VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_F1CE8DEDE8EE98BE ON machines (project_uuid)');
        $this->addSql('CREATE INDEX IDX_F1CE8DED8B8E8428 ON machines (created_at)');
        $this->addSql('CREATE INDEX IDX_F1CE8DED43625D9F ON machines (updated_at)');
        $this->addSql('CREATE TABLE permissions (uuid UUID NOT NULL, user_uuid UUID NOT NULL, create_by_uuid UUID DEFAULT NULL, user_permission_type VARCHAR(255) NOT NULL, related_entity VARCHAR(255) DEFAULT NULL, type_of_machine VARCHAR(255) DEFAULT NULL, related_entity_uuid UUID DEFAULT NULL, reverted BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FABFE1C6F ON permissions (user_uuid)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FC5289E1C ON permissions (create_by_uuid)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6F8B8E8428 ON permissions (created_at)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6F43625D9F ON permissions (updated_at)');
        $this->addSql('CREATE TABLE projects (uuid UUID NOT NULL, client_uuid UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_5C93B3A4E393C4 ON projects (client_uuid)');
        $this->addSql('CREATE INDEX IDX_5C93B3A48B8E8428 ON projects (created_at)');
        $this->addSql('CREATE INDEX IDX_5C93B3A443625D9F ON projects (updated_at)');
        $this->addSql('CREATE TABLE tenant_infrastructure_configuration (host VARCHAR(100) NOT NULL, user_db VARCHAR(100) NOT NULL, password_db VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(host))');
        $this->addSql('CREATE TABLE tenants (host VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(host))');
        $this->addSql('CREATE TABLE users (uuid UUID NOT NULL, email VARCHAR(255) NOT NULL, pub_key VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tenant_name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_1483A5E98B8E8428 ON users (created_at)');
        $this->addSql('CREATE INDEX IDX_1483A5E943625D9F ON users (updated_at)');
        $this->addSql('ALTER TABLE action_user_on_machine ADD CONSTRAINT FK_97DD1CB680B1CB06 FOREIGN KEY (permission_uuid) REFERENCES permissions (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_user_on_machine ADD CONSTRAINT FK_97DD1CB68775BBDF FOREIGN KEY (machine_uuid) REFERENCES machines (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machines ADD CONSTRAINT FK_F1CE8DEDE8EE98BE FOREIGN KEY (project_uuid) REFERENCES projects (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FC5289E1C FOREIGN KEY (create_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4E393C4 FOREIGN KEY (client_uuid) REFERENCES clients (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A4E393C4');
        $this->addSql('ALTER TABLE action_user_on_machine DROP CONSTRAINT FK_97DD1CB68775BBDF');
        $this->addSql('ALTER TABLE action_user_on_machine DROP CONSTRAINT FK_97DD1CB680B1CB06');
        $this->addSql('ALTER TABLE machines DROP CONSTRAINT FK_F1CE8DEDE8EE98BE');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FABFE1C6F');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FC5289E1C');
        $this->addSql('DROP TABLE action_user_on_machine');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE machines');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE tenant_infrastructure_configuration');
        $this->addSql('DROP TABLE tenants');
        $this->addSql('DROP TABLE users');
    }
}
