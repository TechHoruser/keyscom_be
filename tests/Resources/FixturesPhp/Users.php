<?php

namespace App\Tests\Resources\FixturesPhp;

use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionType;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class Users extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "user-%s-%s";

    public function getDependencies(): array
    {
        return [Tenants::class];
    }

    public function load(ObjectManager $manager): void
    {
        for ($tenantIndex = 0; $tenantIndex < $this->fixtureValues->getNumberOfTenants(); ++$tenantIndex) {
            /** @var Tenant $tenant */
            $tenant = $this->getReference(Tenants::PREFIX_REFERENCE . $tenantIndex);
            $adminUser = new User(
                Uuid::uuid4(),
                'admin@keyscom.com',
                null
            );
            $adminUser->setTenantName($tenant->getHost());

            $manager->persist($adminUser);
            $manager->flush();

            $permission = new Permission(
                Uuid::uuid4(),
                null,
                $adminUser,
                PermissionType::ADMIN,
                null,
                null,
                null
            );
            $manager->persist($permission);
            $manager->flush();

            $sshUser = new User(
                Uuid::uuid4(),
                'ssh@keyscom.com',
                null
            );
            $sshUser->setTenantName($tenant->getHost());

            $manager->persist($sshUser);
            $manager->flush();

            $permission = new Permission(
                Uuid::uuid4(),
                $adminUser,
                $sshUser,
                PermissionType::SSH,
                null,
                null,
                null
            );
            $manager->persist($permission);
            $manager->flush();

            $this->addReference(sprintf(self::PREFIX_REFERENCE, $tenantIndex, 'user'), $sshUser);
        }
    }
}
