<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/assigment-permission/rollback/{permissionUuid}",
 *     requirements={"projectUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"POST"},
 * );
 *
 * @OpenApi\Annotations\Post (
 *     path="/assigment-permission/rollback/{permissionUuid}",
 *     summary="Rollback Permission to other user",
 *     tags={"Permissions"},
 * )
 */
class RollbackAssignmentPermissionController extends AbstractCommandController
{
    public function __invoke(string $permissionUuid): JsonResponse
    {
        $results = $this->dispatch(new RollbackPermissionCommand(
            $this->getLoggedUser(),
            $permissionUuid,
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
