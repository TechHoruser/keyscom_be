<?php

namespace App\Tests\UI\Http\Rest\Controller\Users;

use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class GetUserControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::GET;
    const PATH = '/user/%s';

    public function testBasicFunctionality()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $user->getUuid()),
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
