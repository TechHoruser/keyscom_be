<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Clients;

use App\Application\Shared\Enum\EntityType;
use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class DashboardCurrentYearControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/dashboard-current-year';
    const METHOD = self::GET;

    public function testGetClientsSuccessfully()
    {
        // WHEN
        $this->sendRequest(
            self::METHOD,
            self::PATH,
            [
                'type' => EntityType::CLIENTS->value,
            ]
        );
        $response = $this->client->getResponse();

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
    }
}
