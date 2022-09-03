<?php

namespace App\Tests\UI\Http\Rest\Controller\Users;

use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class RollbackAssignmentPermissionControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::POST;
    const PATH = '/assigment-permission/rollback/%s';

    public function testBasicFunctionality()
    {
        // GIVEN
        $this->_em->persist($client = $this->fakerFactory->newClient());
        $this->_em->flush();

        $this->_em->persist($user = $this->fakerFactory->newUser());
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::CLIENT,
            null,
            $client->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        // WHEN
        $response = $this->sendRequest(
            self::METHOD,
            sprintf(self::PATH, $permission->getUuid()),
        );

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
