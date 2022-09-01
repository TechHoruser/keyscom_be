<?php

namespace App\Tests\Integration\UI\Http\Rest\Controller\Users;

use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Tests\Integration\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class AssignmentPermissionControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::POST;
    const PATH = '/assigment-permission';

    public function testBasicFunctionality()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => PermissionType::ADMIN->value,
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testAddPermissionToClient()
    {
        // GIVEN
        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => PermissionType::ADMIN->value,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
