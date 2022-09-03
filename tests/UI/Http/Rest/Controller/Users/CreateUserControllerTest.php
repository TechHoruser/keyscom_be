<?php

namespace App\Tests\UI\Http\Rest\Controller\Projects;

use App\Application\Shared\Helper\DateTimeHelperInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class CreateUserControllerTest extends AbstractControllerIntegrationTest
{
    const PATH = '/user';
    const METHOD = self::POST;

    public function testCreateProjectSuccessfully()
    {
        // GIVEN
        $user = $this->fakerFactory->newUser();
        $userArray = $this->normalizer->normalize($user, self::REQUEST_FORMAT);

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $userArray);

        // THEN
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($user->getUuid(), $responseData['uuid']);
        $this->assertEquals($user->getFirstName(), $responseData['firstName']);
    }

    public function testCreateUserWithRepeatedEmail()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();
        $userArray = $this->normalizer->normalize($user, self::REQUEST_FORMAT);

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $userArray);

        // THEN
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
