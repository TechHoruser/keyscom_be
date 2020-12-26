<?php

namespace App\Tests\Resources\Fixtures;

use App\Domain\Client\Entity\Client;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Projects extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "project-";

    public function getDependencies()
    {
        return [Clients::class];
    }

    public function load(ObjectManager $manager)
    {
        for ($clientIndex = 0; $clientIndex < $this->fixtureValues->getNumberOfClients(); ++$clientIndex) {
            $numberOfProjects = rand(1, $this->fixtureValues->getMaxProjectsPerClient());
            for ($projectIndex = 0; $projectIndex < $numberOfProjects; ++$projectIndex) {
                /** @var Client $client */
                $client = $this->getReference(Clients::PREFIX_REFERENCE . $clientIndex);
                $project = $this->fakerFactory->newProject($client);

                $manager->persist($project);
                $manager->flush();

                $this->addReference(self::PREFIX_REFERENCE . $clientIndex . '-' . $projectIndex, $project);
            }
        }
    }
}
