<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725084539 extends AbstractMigration
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
        $this->addSql('ALTER TABLE action_user_on_machine ADD CONSTRAINT FK_97DD1CB680B1CB06 FOREIGN KEY (permission_uuid) REFERENCES permissions (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_user_on_machine ADD CONSTRAINT FK_97DD1CB68775BBDF FOREIGN KEY (machine_uuid) REFERENCES machines (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD create_by_uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE permissions ALTER user_uuid SET NOT NULL');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FC5289E1C FOREIGN KEY (create_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FC5289E1C ON permissions (create_by_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE action_user_on_machine');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FC5289E1C');
        $this->addSql('DROP INDEX IDX_2DEDCC6FC5289E1C');
        $this->addSql('ALTER TABLE permissions DROP create_by_uuid');
        $this->addSql('ALTER TABLE permissions ALTER user_uuid DROP NOT NULL');
    }
}
