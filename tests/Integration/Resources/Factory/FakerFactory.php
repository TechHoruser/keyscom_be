<?php

declare(strict_types=1);

namespace App\Tests\Integration\Resources\Factory;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\User;
use App\Tests\Integration\Resources\Config\FixtureValuesInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerFactory implements FakerFactoryInterface
{
    private Generator $faker;

    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private FixtureValuesInterface $fixtureValues,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        $this->faker = Factory::create();
    }

    public function getUserPassword(): string
    {
        return $this->fixtureValues->getCommonUserPassword();
    }

    public function newUser(?Tenant $tenant = null): User
    {
        $user = new User(
            $this->faker->uuid3(),
            $this->faker->email(),
            null,
            null,
            $this->faker->firstName(),
            $this->faker->lastName(),
        );
        $user->setPassword($this->passwordHasher->hashPassword(
            \App\Infrastructure\Security\User::createFromDomainUser($user),
            $this->fixtureValues->getCommonUserPassword(),
        ));
        $user->setTenant($tenant);
        $user->setCreatedAt($this->faker->dateTimeBetween((new \DateTime('2021-11-01'))));
        return $user;
    }

    public function newClient(?Tenant $tenant = null): Client
    {
        $client = new Client(
            $this->faker->uuid3(),
            $this->faker->name(),
        );
        $client->setTenant($tenant);
        $client->setCreatedAt($this->faker->dateTimeBetween((new \DateTime('2021-11-01'))));
        return $client;
    }

    public function newProject(?Client $client = null): Project
    {
        $client = is_null($client) ?
            $this->newClient() :
            $this->clientRepository->getByUuid($client->getUuid())
        ;

        $startDate = $this->faker->dateTime();
        $project = new Project(
            $this->faker->uuid3(),
            $this->faker->name(),
            $startDate,
            $this->faker->boolean() ? $this->faker->dateTimeBetween($startDate) : null,
            $client,
        );
        $project->setTenantName($client->getTenantName());
        $project->setCreatedAt($this->faker->dateTimeBetween((new \DateTime('2021-11-01'))));
        return $project;
    }

    public function newMachine(?Project $project = null): Machine
    {
        if (is_null($project)) {
            $project = $this->newProject();
        }

        $machine = new Machine(
            $this->faker->uuid3(),
            $this->faker->ipv4(),
            $this->faker->name(),
            $this->faker->domainName(),
            null,
            $project,
        );
        $machine->setTenantName($project->getTenantName());
        $machine->setCreatedAt($this->faker->dateTimeBetween((new \DateTime('2021-11-01'))));
        return $machine;
    }
}
