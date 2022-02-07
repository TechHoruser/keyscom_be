<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Clients;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GetClientsControllerTest extends AbstractControllerIntegrationTest implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            '/var/www/html/tests/Resources/Fixtures/yml/Clients.yml',
        ];
    }

    public function testGetClientsSuccessfully()
    {
        // GIVEN
        $method = self::GET;
        $path = '/client';

        // WHEN
        $this->client->request($method, $path);
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
        $numberOfClientsOnFixtures = 2;
        $this->assertEquals($numberOfClientsOnFixtures, $responseData['totalItems']);
    }
}
