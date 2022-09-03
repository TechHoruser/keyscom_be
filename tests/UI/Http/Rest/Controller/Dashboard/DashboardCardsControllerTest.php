<?php

namespace App\Tests\UI\Http\Rest\Controller\Clients;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;

class DashboardCardsControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/dashboard-card';
    const METHOD = self::GET;

    public function testGetClientsSuccessfully()
    {
        // WHEN
        $this->sendRequest(self::METHOD, self::PATH);
        $response = $this->client->getResponse();

        // THEN
        $this->assertEquals(200, $response->getStatusCode());
    }
}
