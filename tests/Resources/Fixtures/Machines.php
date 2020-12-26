<?php

namespace App\Tests\Resources\Fixtures;

use App\Domain\Client\Entity\Client;
use App\Domain\Project\Entity\Project;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Machines extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "machine-";

    public function getDependencies()
    {
        return [Projects::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($clientIndex = 0; $clientIndex < $this->fixtureValues->getNumberOfClients(); ++$clientIndex) {
            /** @var Client $client */
            $client = $this->getReference(Clients::PREFIX_REFERENCE . $clientIndex);
            foreach ($client->getProjects() as $project) {
                $numberOfMachines = rand(1, $this->fixtureValues->getMaxMachinesPerProject());
                for ($machineIndex = 0; $machineIndex < $numberOfMachines; ++$machineIndex) {
                    $machine = $this->fakerFactory->newMachine($project);

                    $manager->persist($machine);
                    $manager->flush();
                }
            }
        }
    }
}
