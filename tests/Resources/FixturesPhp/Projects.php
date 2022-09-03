<?php

namespace App\Tests\Resources\FixturesPhp;

use App\Domain\Client\Entity\Client;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Projects extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "project-%s-%s";

    public function getDependencies(): array
    {
        return [Clients::class];
    }

    public function load(ObjectManager $manager)
    {
        $allowReference = explode("-", Clients::PREFIX_REFERENCE, 2)[0];
        foreach (array_keys($this->referenceRepository->getIdentities()) as $savedReference) {
            if (!str_starts_with($savedReference, $allowReference)) continue;

            $numberOfProjects = rand(1, $this->fixtureValues->getMaxProjectsPerClient());
            for ($projectIndex = 0; $projectIndex < $numberOfProjects; ++$projectIndex) {
                /** @var Client $client */
                $client = $this->getReference($savedReference);
                $project = $this->fakerFactory->newProject($client);

                $manager->persist($project);
                $manager->flush();

                $this->addReference(
                    sprintf(self::PREFIX_REFERENCE, explode('-', $savedReference, 2)[1], $projectIndex),
                    $project
                );
            }
        }
    }
}
