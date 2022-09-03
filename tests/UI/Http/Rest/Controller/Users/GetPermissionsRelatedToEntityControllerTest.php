<?php

namespace App\Tests\UI\Http\Rest\Controller\Users;

use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class GetPermissionsRelatedToEntityControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::GET;
    const PATH = '/permission-related-to-entity';

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

        $queryParams = [
            'permissionRelatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'permissionRelatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, $queryParams);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
