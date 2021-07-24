<?php

namespace App\Tests\Integration\Infrastructure\Persistence\Doctrine\Repository;

use App\Application\Shared\Helper\DateTimeHelperInterface;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Machine\Entity\Machine;
use App\Domain\Machine\Repository\MachineRepositoryInterface;
use App\Domain\Project\Entity\Project;
use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\User\Entity\Permission;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\PermissionRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class PermissionRepositoryTest extends WebTestCase
{
    use FixturesTrait;

    private PermissionRepositoryInterface $permissionRepository;
    private DateTimeHelperInterface $dateTimeHelper;

    private User $user;
    /** @var Client[] $clients */
    private $clients;
    /** @var Project[] $projects */
    private $projects;
    /** @var Machine[] $machines */
    private $machines;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->permissionRepository = self::$container->get(PermissionRepositoryInterface::class);
        $this->dateTimeHelper = self::$container->get(DateTimeHelperInterface::class);

        $this->loadFixtureFiles([
            '/var/www/html/tests/Resources/Fixtures/yml/UsersAndPermissions.yml',
        ]);

        $this->setTestVariables();
    }

    private function setTestVariables()
    {
        $userRepository = self::$container->get(UserRepositoryInterface::class);
        $this->user = $userRepository->complexFind(0,
            0,
            null,
            null,
            ['email' => 'developer@keyscom.com']
        )[0];

        $createAssociativeArrayByName = fn($array) => array_column(
            array_map(
                fn ($k, $v) => [$v->getName(), $v],
                array_keys($array),
                $array
            ),
            1,
            0
        );

        $this->clients = $createAssociativeArrayByName(
            (self::$container->get(ClientRepositoryInterface::class))->complexFind()
        );

        $this->projects = $createAssociativeArrayByName(
            (self::$container->get(ProjectRepositoryInterface::class))->complexFind()
        );

        $this->machines = $createAssociativeArrayByName(
            (self::$container->get(MachineRepositoryInterface::class))->complexFind()
        );
    }

    public function testIfGetChildPermissionsOfUserReturnSuccessfullyV1()
    {
        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
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
            'ssh',
            'client',
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
            'ssh',
            'project',
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
            'ssh',
            'machine',
            null,
            $this->machines['machine_1_1_1']->getUuid()
        );

        // THEN
        $this->assertEquals(0, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV1()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'client',
                null,
                $this->clients['client_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
            null,
            null,
            null
        );

        // THEN
        $this->assertEquals(1, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV2()
    {
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'project',
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'project',
                null,
                $this->projects['project_1_2']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
            null,
            null,
            null
        );

        // THEN
        $this->assertEquals(2, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV3()
    {
        $permission = $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'project',
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
            'client',
            null,
            $this->clients['client_1']->getUuid()
        );

        // THEN
        $this->assertEquals(1, count($results));
    }

    public function testIfGetChildPermissionsWhitThatOfUserReturnSuccessfullyV4()
    {
        $permission = $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'machine',
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );
        $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'project',
                null,
                $this->projects['project_1_2']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
            'client',
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
            'ssh',
            'machine',
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
                $this->user,
                'ssh',
                'machine',
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );

        $this->permissionRepository->save(
            new Permission(
                null,
                $this->user,
                'ssh',
                'project',
                null,
                $this->projects['project_1_1']->getUuid()
            )
        );

        // WHEN
        $results = $this->permissionRepository->getChildPermissionsOfUser(
            $this->user->getUuid(),
            'ssh',
            'client',
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
            'ssh',
            'client',
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
            'ssh',
            'project',
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
            'ssh',
            'machine',
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
            'ssh',
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
                $this->user,
                'ssh',
                'project',
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
                $this->user,
                'ssh',
                'machine',
                null,
                $this->machines['machine_1_1_1']->getUuid()
            )
        );
    }
}
