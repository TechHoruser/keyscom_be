<?php

declare(strict_types=1);

namespace App\Tests\Integration\Resources\Factory;

use App\Domain\Client\Entity\Client;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Project\Entity\Project;
use App\Domain\Tenant\Entity\Tenant;
use App\Domain\User\Entity\User;

interface FakerFactoryInterface
{
    public function newUser(?Tenant $tenant): User;
    public function getUserPassword(): string;
    public function newClient(?Tenant $tenant): Client;
    public function newProject(?Client $client): Project;
    public function newMachine(?Project $project): Machine;
}
