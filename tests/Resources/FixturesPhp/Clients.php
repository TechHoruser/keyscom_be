<?php

namespace App\Tests\Resources\FixturesPhp;

use App\Domain\Tenant\Entity\Tenant;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Clients extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "client-%s-%s";

    public function getDependencies(): array
    {
        return [Tenants::class];
    }

    public function load(ObjectManager $manager): void
    {
        for ($tenantIndex = 0; $tenantIndex < $this->fixtureValues->getNumberOfTenants(); ++$tenantIndex) {
            for ($clientIndex = 0; $clientIndex < $this->fixtureValues->getNumberOfClients(); ++$clientIndex) {
                /** @var Tenant $tenant */
                $tenant = $this->getReference(Tenants::PREFIX_REFERENCE . $tenantIndex);
                $client = $this->fakerFactory->newClient($tenant);

                $manager->persist($client);
                $manager->flush();

                $this->addReference(sprintf(self::PREFIX_REFERENCE, $tenantIndex, $clientIndex), $client);
            }
        }
    }
}
