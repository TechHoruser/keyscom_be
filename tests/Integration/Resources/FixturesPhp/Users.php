<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Application\Shared\Helper\SecurityHelperInterface;
use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionType;
use App\Infrastructure\Security\User;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use App\Tests\Integration\Resources\Factory\FakerFactoryInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Users extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "user-%s-%s";

    public function __construct(
        FixtureValuesInterface $fixtureValues,
        SecurityHelperInterface $securityHelper,
        FakerFactoryInterface $fakerFactory,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($fixtureValues, $securityHelper, $fakerFactory);
    }

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
                null,
                null,
                'Admin User',
            );
            $adminUser->setPassword($this->passwordHasher->hashPassword(
                $adminUser,
                $this->fixtureValues->getCommonUserPassword(),
            ));
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
                null,
                null,
                'Ssh User',
            );
            $sshUser->setPassword($this->passwordHasher->hashPassword(
                $sshUser,
                $this->fixtureValues->getCommonUserPassword(),
            ));
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
