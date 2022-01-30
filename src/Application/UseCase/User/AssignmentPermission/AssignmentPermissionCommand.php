<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignmentPermission;

use App\Application\Shared\Command\CommandInterface;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;

class AssignmentPermissionCommand implements CommandInterface
{
    private string $uuidOfUserWhoGivesPermissions;
    private string $userUuid;
    private PermissionType $userPermissionType;
    private ?PermissionRelatedEntity $typeRelatedEntity;
    private ?string $typeOfMachine;
    private ?string $relatedEntityUuid;

    public function __construct(
        string $uuidOfUserWhoGivesPermissions,
        string $userUuid,
        PermissionType $userPermissionType,
        ?PermissionRelatedEntity $typeRelatedEntity,
        ?string $typeOfMachine,
        ?string $relatedEntityUuid
    ) {
        $this->uuidOfUserWhoGivesPermissions = $uuidOfUserWhoGivesPermissions;
        $this->userUuid = $userUuid;
        $this->userPermissionType = $userPermissionType;
        $this->typeRelatedEntity = $typeRelatedEntity;
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
     * @return PermissionType
     */
    public function getUserPermissionType(): PermissionType
    {
        return $this->userPermissionType;
    }

    /**
     * @param PermissionType $userPermissionType
     *
     * @return $this
     */
    public function setUserPermissionType(PermissionType $userPermissionType): static
    {
        $this->userPermissionType = $userPermissionType;

        return $this;
    }

    /**
     * @return PermissionRelatedEntity|null
     */
    public function getTypeRelatedEntity(): ?PermissionRelatedEntity
    {
        return $this->typeRelatedEntity;
    }

    /**
     * @param PermissionRelatedEntity|null $typeRelatedEntity
     *
     * @return $this
     */
    public function setTypeRelatedEntity(?PermissionRelatedEntity $typeRelatedEntity): static
    {
        $this->typeRelatedEntity = $typeRelatedEntity;

        return $this;
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
