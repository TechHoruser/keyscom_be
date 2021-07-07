<?php

declare(strict_types=1);

namespace App\Tests\Resources\Config;

class FixtureValues implements FixtureValuesInterface
{
    private int $numberOfClients;
    private int $maxProjectsPerClient;
    private int $maxMachinesPerProject;
    private int $numberOfTenants;

    public function __construct()
    {
        $ini = file_exists(dirname(__FILE__) . '/IniFiles/values.ini') ?
            array_merge(
                parse_ini_file('IniFiles/default_values.ini'),
                parse_ini_file('IniFiles/values.ini')
            ) :
            parse_ini_file('IniFiles/default_values.ini');

        $this->numberOfClients = intval($ini['NUMBER_OF_CLIENTS']);
        $this->maxProjectsPerClient = intval($ini['MAX_NUMBER_OF_PROJECTS_PER_CLIENT']);
        $this->maxMachinesPerProject = intval($ini['MAX_NUMBER_OF_MACHINES_PER_PROJECT']);
        $this->numberOfTenants = intval($ini['NUMBER_OF_TENANTS']);
    }

    /**
     * @return int
     */
    public function getNumberOfClients(): int
    {
        return $this->numberOfClients;
    }

    /**
     * @return int
     */
    public function getMaxProjectsPerClient(): int
    {
        return $this->maxProjectsPerClient;
    }

    /**
     * @return int
     */
    public function getMaxMachinesPerProject(): int
    {
        return $this->maxMachinesPerProject;
    }

    /**
     * @return int
     */
    public function getNumberOfTenants(): int
    {
        return $this->numberOfTenants;
    }
}
