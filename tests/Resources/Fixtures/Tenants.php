<?php

namespace App\Tests\Resources\Fixtures;

use App\Domain\Tenant\Entity\Tenant;
use App\Infrastructure\Tenant\Entity\TenantConfiguration;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Tenants extends AbstractFixture implements FixtureInterface
{
    public const PREFIX_REFERENCE = "tenant-";
    private const TENANT_PREFIX_NAME = 'tenant';

    public function load(ObjectManager $manager)
    {
        $this->removeUsersInDb($manager);

        for ($tenantIndex = 0; $tenantIndex < $this->fixtureValues->getNumberOfTenants(); ++$tenantIndex) {
            $tenantString = getenv('APP_ENV') . '_' . self::TENANT_PREFIX_NAME . $tenantIndex;
            $tenantHost = $tenantString . '.' . getenv('DOMAIN');

            $tenantConfig = new TenantConfiguration(
                $tenantHost,
                $tenantString,
                $this->securityHelper->encryptString($tenantString)
            );

            $tenant = new Tenant($tenantHost, $tenantConfig);

            $manager->persist($tenantConfig);
            $manager->flush();
            $manager->persist($tenant);
            $manager->flush();

            $this->createUserInDb($manager, $tenantString, $tenantString);

            $this->addReference(self::PREFIX_REFERENCE . $tenantIndex, $tenant);
        }
    }


    private function createUserInDb(ObjectManager $manager, string $userDb, string $passwordDb): void
    {
        $params = [
            ':user' => $userDb,
            ':password' => "'" . $passwordDb . "'",
            ':db' => getenv('POSTGRES_DB'),
        ];

        $createUserQuery = sprintf(
            'CREATE USER %s WITH PASSWORD %s',
            $params[':user'],
            $params[':password']
        );
        $grantPrivilegesOnDatabase = sprintf(
            'GRANT ALL PRIVILEGES ON DATABASE "%s" to %s',
            $params[':db'],
            $params[':user']
        );
        $grantActionsPrivileges = sprintf(
            'GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO %s',
            $params[':user']
        );


        $manager->getConnection()->executeStatement($createUserQuery);
        $manager->getConnection()->executeStatement($grantPrivilegesOnDatabase);
        $manager->getConnection()->executeStatement($grantActionsPrivileges);
    }


    private function removeUsersInDb(ObjectManager $manager): void
    {
        $getAllUsersQuery = sprintf(
            'SELECT usename FROM pg_catalog.pg_user WHERE usename LIKE \'%s%%\'',
            getenv('APP_ENV') . '_' . self::TENANT_PREFIX_NAME
        );
        $users = $manager->getConnection()->fetchAllAssociative($getAllUsersQuery);

        foreach ($users as $user) {
            $params = [
                ':user' => $user['usename'],
                ':db' => getenv('POSTGRES_DB'),
            ];

            $revokeAllPrivilegesInTablesQuery = sprintf(
                'REVOKE ALL PRIVILEGES ON ALL TABLES IN SCHEMA public FROM %s;',
                $params[':user']
            );
            $revokeAllPrivilegesInDbQuery = sprintf(
                'revoke all privileges on database "%s" FROM %s',
                $params[':db'],
                $params[':user']
            );
            $removeUserQuery = sprintf(
                'drop user %s',
                $params[':user']
            );


            $manager->getConnection()->executeStatement($revokeAllPrivilegesInTablesQuery);
            $manager->getConnection()->executeStatement($revokeAllPrivilegesInDbQuery);
            $manager->getConnection()->executeStatement($removeUserQuery);
        }
    }
}
