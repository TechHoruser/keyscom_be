<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\GetUsersByPermissions\GetUsersWithPermissionsQuery;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/permission-related-to-entity", methods={"GET"})
 *
 * @OpenApi\Annotations\Get (
 *     path="/permission-related-to-entity",
 *     summary="Get Permissions related to an Entity",
 *     tags={"Permissions"},
 *     @OpenApi\Annotations\Parameter (
 *         name="permissionType",
 *         in="query",
 *         description="The number of page to show"
 *     ),
 *     @OpenApi\Annotations\Parameter (
 *         name="permissionRelatedEntity",
 *         in="query",
 *         description="The number of page to show"
 *     ),
 *     @OpenApi\Annotations\Parameter (
 *         name="permissionRelatedEntityUuid",
 *         in="query",
 *         description="The number of page to show"
 *     ),
 * )
 */
class GetPermissionsRelatedToEntityController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new GetUsersWithPermissionsQuery(
//            PermissionType::from($this->request->query->get('permissionType')),
            null,
            PermissionRelatedEntity::from($this->request->query->get('permissionRelatedEntity')),
            $this->request->query->get('permissionRelatedEntityUuid'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
