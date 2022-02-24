<?php

namespace App\Tests\Integration\Resources\FixturesPhp;

use App\Domain\Project\Entity\Project;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function App\Tests\Resources\FixturesPhp\str_starts_with;

class Machines extends AbstractFixture implements DependentFixtureInterface
{
    public const PREFIX_REFERENCE = "machine-%s-%s";

    public function getDependencies(): array
    {
        return [Projects::class];
    }

    public function load(ObjectManager $manager)
    {
        $allowReference = explode("-", Projects::PREFIX_REFERENCE, 2)[0];
        foreach (array_keys($this->referenceRepository->getIdentities()) as $savedReference) {
            if (!str_starts_with($savedReference, $allowReference)) continue;

            $numberOfMachines = rand(1, $this->fixtureValues->getMaxMachinesPerProject());
            for ($machineIndex = 0; $machineIndex < $numberOfMachines; ++$machineIndex) {
                /** @var Project $project */
                $project = $this->getReference($savedReference);
                $machine = $this->fakerFactory->newMachine($project);

                $manager->persist($machine);
                $manager->flush();

                $this->addReference(
                    sprintf(self::PREFIX_REFERENCE, explode('-', $savedReference, 2)[1], $machineIndex),
                    $machine
                );
            }
        }
    }
}
