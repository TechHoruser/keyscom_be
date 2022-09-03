<?php

namespace App\Tests\Resources\Config;

interface FixtureValuesInterface
{
    public function getNumberOfClients(): int;
    public function getMaxProjectsPerClient(): int;
    public function getMaxMachinesPerProject(): int;
    public function getNumberOfTenants(): int;
    public function getCommonUserPassword(): string;
}
