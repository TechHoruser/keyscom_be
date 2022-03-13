<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Entity\Project;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Shared\Entities\PaginationProperties;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class PermissionRepositoryTest extends WebTestCase
{
    private $permissionRepository;

    private User $user;
    private User $adminUser;
    /** @var Client[] $clients */
    private array $clients;
    /** @var Project[] $projects */
    private array $projects;
    /** @var Machine[] $machines */
    private array $machines;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->permissionRepository = static::getContainer()->get(PermissionRepositoryInterface::class);
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $databaseTool->loadAliceFixture([
            '/var/www/html/tests/Integration/Resources/FixturesYml/Users.yml',
            '/var/www/html/tests/Integration/Resources/FixturesYml/Clients.yml',
            '/var/www/html/tests/Integration/Resources/FixturesYml/Projects.yml',
            '/var/www/html/tests/Integration/Resources/FixturesYml/Machines.yml',
        ]);

        $this->setTestVariables();
    }

    private function setTestVariables()
    {
        $userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->user = $userRepository->complexFind(
            (new PaginationProperties()),
            ['email' => 'ssh@keyscom.com'],
        )[0];
        $this->adminUser = $userRepository->complexFind(
            (new PaginationProperties()),
            ['email' => 'admin@keyscom.com'],
        )[0];

        $createAssociativeArrayByName = static fn($array) => array_column(
            array_map(
                static fn ($k, Client|Machine|Project $v) => [$v->getName(), $v],
                array_keys($array),
                $array
            ),
            1,
            0
        );

        $this->clients = $createAssociativeArrayByName(
            (static::getContainer()->get(ClientRepositoryInterface::class))->complexFind()
        );

        $this->projects = $createAssociativeArrayByName(
            (static::getContainer()->get(ProjectRepositoryInterface::class))->complexFind()
        );

        $this->machines = $createAssociativeArrayByName(
            (static::getContainer()->get(MachineRepositoryInterface::class))->complexFind()
        );
    }

    public function testIfGetChildPermissionsOfUserReturnSuccessfullyV1()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            null,
            null,
            null
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsOfUserReturnSuccessfullyV2()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsOfUserReturnSuccessfullyV3()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $this->projects['project_1_1']->getUuid()
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsOfUserReturnSuccessfullyV4()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::MACHINE,
            null,
            $this->machines['machine_1_1_1']->getUuid()
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyGivenAClient()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::CLIENT,
                null,
                $this->clients['client_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            null,
            null,
            null
        );

        // THEN
        $this->assertEquals(1, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyGivenTwoProjects()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_2']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            null,
            null,
            null
        );

        // THEN
        $this->assertEquals(2, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyGivenAProjectAndAClient()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertEquals(1, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV4()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::MACHINE,
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_2']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertEquals(2, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV5()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::MACHINE,
            null,
            $this->machines['machine_1_1_1']->getUuid()
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV6()
    {
        // GIVE
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::MACHINE,
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );

        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertEquals(1, count($results));
    }

    public function testIfGetParentPermissionWithoutParentPermissionV1()
    {
        // WHEN
        $result = $this->permissionRepository->getParentOrSamePermissionOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::CLIENT,
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertNull($result);
    }

    public function testIfGetParentPermissionWithoutParentPermissionV2()
    {
        // WHEN
        $result = $this->permissionRepository->getParentOrSamePermissionOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::PROJECT,
            null,
            $this->projects['project_1_1']->getUuid()
        );

        // THEN
        $this->assertNull($result);
    }

    public function testIfGetParentPermissionWithoutParentPermissionV3()
    {
        // WHEN
        $result = $this->permissionRepository->getParentOrSamePermissionOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            PermissionRelatedEntity::MACHINE,
            null,
            $this->machines['machine_1_1_1']->getUuid()
        );

        // THEN
        $this->assertNull($result);
    }

    public function testIfGetParentPermissionWithoutParentPermissionV4()
    {
        // WHEN
        $result = $this->permissionRepository->getParentOrSamePermissionOfUser(
            $this->user->getUuid(),
            PermissionType::SSH,
            null,
            null,
            null
        );

        // THEN
        $this->assertNull($result);
    }

    public function testIfGetParentPermissionWithoutParentPermissionV5()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::PROJECT,
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );

        // EXPECT
        $this->expectException(\Exception::class);

        // WHEN
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->adminUser,
                $this->user,
                PermissionType::SSH,
                PermissionRelatedEntity::MACHINE,
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );
    }
}
