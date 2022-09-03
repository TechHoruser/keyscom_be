<?php

namespace App\Tests\UI\Http\Rest\Controller\Users;

use App\Domain\User\Entity\Permission;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Tests\UI\Http\Rest\Controller\AbstractControllerIntegrationTest;
use Symfony\Component\HttpFoundation\Response;

class RevokePermissionsByEntityControllerTest extends AbstractControllerIntegrationTest
{
    const METHOD = self::DELETE;
    const PATH = '/revoke-permissions';

    private PermissionRepositoryInterface $permissionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $container = static::getContainer();

        /** @var PermissionRepositoryInterface $permissionRepository */
        $permissionRepository = $container->get(PermissionRepositoryInterface::class);
        $this->permissionRepository = $permissionRepository;
    }

    public function testBasicFunctionality()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
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

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => PermissionType::ADMIN->value,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }

    public function testRemoveAllIndicatePermission()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
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

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $client->getUuid(),
        );
        $this->_em->persist($permission);
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
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }

    public function testRemoveAllPermissionTypes()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
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

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $client->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => null,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }

    public function testRemoveChildPermissionsWithAdminType()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $project1 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project1);
        $this->_em->flush();

        $project2 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project2);
        $this->_em->flush();

        $machine = $this->fakerFactory->newMachine($project2);
        $this->_em->persist($machine);
        $this->_em->flush();

        $dummyClient = $this->fakerFactory->newClient();
        $this->_em->persist($dummyClient);
        $this->_em->flush();

        $dummyProject = $this->fakerFactory->newProject($dummyClient);
        $this->_em->persist($dummyProject);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project2->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::MACHINE,
            null,
            $machine->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $dummyProject->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => PermissionType::ADMIN,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(3, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }

    public function testRemoveChildPermissionsWithoutType()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $project1 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project1);
        $this->_em->flush();

        $project2 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project2);
        $this->_em->flush();

        $machine = $this->fakerFactory->newMachine($project2);
        $this->_em->persist($machine);
        $this->_em->flush();

        $dummyClient = $this->fakerFactory->newClient();
        $this->_em->persist($dummyClient);
        $this->_em->flush();

        $dummyProject = $this->fakerFactory->newProject($dummyClient);
        $this->_em->persist($dummyProject);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project2->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::MACHINE,
            null,
            $machine->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $dummyProject->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => null,
            'relatedEntity' => PermissionRelatedEntity::CLIENT->value,
            'relatedEntityUuid' => $client->getUuid(),
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }

    public function testRemoveAllPermissionsFromUser()
    {
        // GIVEN
        $client = $this->fakerFactory->newClient();
        $this->_em->persist($client);
        $this->_em->flush();

        $project1 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project1);
        $this->_em->flush();

        $project2 = $this->fakerFactory->newProject($client);
        $this->_em->persist($project2);
        $this->_em->flush();

        $machine = $this->fakerFactory->newMachine($project2);
        $this->_em->persist($machine);
        $this->_em->flush();

        $dummyClient = $this->fakerFactory->newClient();
        $this->_em->persist($dummyClient);
        $this->_em->flush();

        $dummyProject = $this->fakerFactory->newProject($dummyClient);
        $this->_em->persist($dummyProject);
        $this->_em->flush();

        $user = $this->fakerFactory->newUser();
        $this->_em->persist($user);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project1->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $project2->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::MACHINE,
            null,
            $machine->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $permission = new Permission(
            null,
            $this->getUserToken(),
            $user,
            PermissionType::ADMIN,
            PermissionRelatedEntity::PROJECT,
            null,
            $dummyProject->getUuid(),
        );
        $this->_em->persist($permission);
        $this->_em->flush();

        $request = [
            'userUuid' => $user->getUuid(),
            'userPermissionType' => null,
            'relatedEntity' => null,
            'relatedEntityUuid' => null,
        ];

        // WHEN
        $response = $this->sendRequest(self::METHOD, self::PATH, [], $request);

        // THEN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(0, $this->permissionRepository->permissionsOfUser($user->getUuid()));
    }
}
