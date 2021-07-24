<?php

namespace App\Tests\Resources\Fixtures;

use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class Users extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "user-%s-%s";

    public function getDependencies()
    {
        return [Tenants::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($tenantIndex = 0; $tenantIndex < $this->fixtureValues->getNumberOfTenants(); ++$tenantIndex) {
            /** @var Tenant $tenant */
            $tenant = $this->getReference(Tenants::PREFIX_REFERENCE . $tenantIndex);
            $user = new User(
                Uuid::uuid4(),
                'admin@keyscom.com',
                null
            );
            $user->setTenantName($tenant->getHost());

            $manager->persist($user);
            $manager->flush();

            $permission = new Permission(
                Uuid::uuid4(),
                $user,
                'ssh',
                null,
                null,
                null
            );
            $manager->persist($permission);
            $manager->flush();

            $permission = new Permission(
                Uuid::uuid4(),
                $user,
                'admin',
                null,
                null,
                null
            );
            $manager->persist($permission);
            $manager->flush();

            $user = new User(
                Uuid::uuid4(),
                'user@keyscom.com',
                null
            );
            $user->setTenantName($tenant->getHost());

            $manager->persist($user);
            $manager->flush();

            $this->addReference(sprintf(self::PREFIX_REFERENCE, $tenantIndex, 'user'), $user);
        }
    }
}
