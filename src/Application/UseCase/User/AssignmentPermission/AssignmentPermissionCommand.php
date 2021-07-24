<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandInterface;

class AssignmentPermissionCommand implements CommandInterface
{
    private string $uuidOfUserWhoGivesPermissions;
    private string $userUuid;
    private string $userPermissionType;
    /** @var null|string('machine', 'project', 'client') $relatedEntity */
    private ?string $relatedEntity;
    private ?string $typeOfMachine;
    private ?string $relatedEntityUuid;

    /**
     * AssignmentPermissionCommand constructor.
     * @param string $uuidOfUserWhoGivesPermissions
     * @param string $userUuid
     * @param string $userPermissionType
     * @param string|null $relatedEntity
     * @param string|null $typeOfMachine
     * @param string|null $relatedEntityUuid
     */
    public function __construct(
        string $uuidOfUserWhoGivesPermissions,
        string $userUuid,
        string $userPermissionType,
        ?string $relatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ) {
        $this->uuidOfUserWhoGivesPermissions = $uuidOfUserWhoGivesPermissions;
        $this->userUuid = $userUuid;
        $this->userPermissionType = $userPermissionType;
        $this->relatedEntity = $relatedEntity;
        $this->typeOfMachine = $typeOfMachine;
        $this->relatedEntityUuid = $relatedEntityUuid;
    }

    /**
     * @return string
     */
    public function getUuidOfUserWhoGivesPermissions(): string
    {
        return $this->uuidOfUserWhoGivesPermissions;
    }

    /**
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    /**
     * @return string
     */
    public function getUserPermissionType(): string
    {
        return $this->userPermissionType;
    }

    /**
     * @return string|null
     */
    public function getRelatedEntity(): ?string
    {
        return $this->relatedEntity;
    }

    /**
     * @return string|null
     */
    public function getTypeOfMachine(): ?string
    {
        return $this->typeOfMachine;
    }

    /**
     * @return string|null
     */
    public function getRelatedEntityUuid(): ?string
    {
        return $this->relatedEntityUuid;
    }
}
