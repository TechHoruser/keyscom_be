<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Users;

use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class GetUsersControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::GET;
    const PATH = '/user';

    public function testBasicFunctionality()
    {
        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
