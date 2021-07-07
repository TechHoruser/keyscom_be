<?php

declare(strict_types=1);

namespace App\Tests\Resources\Factory;

use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Client\Entity\Client;
use App\Domain\Tenant\Entity\Tenant;
use Faker\Factory;
use Faker\Generator;

class FakerFactory implements FakerFactoryInterface
{
    private Generator $faker;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(
        ClientRepositoryInterface $clientRepository
    ) {
        $this->faker = Factory::create();
        $this->clientRepository = $clientRepository;
    }

    public function newClient(?Tenant $tenant = null): Client
    {
        $client = new Client(
            $this->faker->uuid,
            $this->faker->name
        );
        $client->setTenant($tenant);
        $client->setCreatedAt(new \DateTime());
        return $client;
    }

    public function newProject(?Client $client = null): Project
    {
        $client = is_null($client) ?
            $this->newClient() :
            $this->clientRepository->getByUuid($client->getUuid())
        ;

        $startDate = $this->faker->dateTime;
        $contract = new Project(
            $this->faker->uuid,
            $this->faker->name,
            $startDate,
            $this->faker->boolean ? $this->faker->dateTimeBetween($startDate) : null,
            $client
        );
        $contract->setTenantName($client->getTenantName());
        $contract->setCreatedAt(new \DateTime());
        return $contract;
    }

    public function newMachine(?Project $project = null): Machine
    {
        if (is_null($project)) {
            $project = $this->newProject();
        }

        $machine = new Machine(
            $this->faker->uuid,
            $this->faker->ipv4,
            $this->faker->domainName,
            null,
            $project
        );
        $machine->setTenantName($project->getTenantName());
        $machine->setCreatedAt(new \DateTime());
        return $machine;
    }
}
