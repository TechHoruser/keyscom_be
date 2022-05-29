<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RevokePermissionsByEntity\RevokePermissionsByEntityCommand;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/revoke-permissions",
 *     methods={"DELETE"},
 * );
 *
 * @OpenApi\Annotations\Delete (
 *     path="/revoke-permissions",
 *     summary="Revoke Permissions",
 *     tags={"Permissions"},
 * )
 */
class RevokePermissionsByEntityController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new RevokePermissionsByEntityCommand(
            $this->getLoggedUser(),
            $this->request->request->get('userUuid'),
            is_null($permissionType = $this->request->request->get('userPermissionType')) ?
                null :
                PermissionType::from($permissionType)
            ,
            is_null($typeRelatedEntity = $this->request->request->get('relatedEntity')) ?
                null :
                PermissionRelatedEntity::from($typeRelatedEntity)
            ,
            $this->request->request->get('relatedEntityUuid'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
