<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\AssignmentPermission\AssignmentPermissionCommand;
use App\Domain\User\Enums\PermissionRelatedEntity;
use App\Domain\User\Enums\PermissionType;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/assigment-permission", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/assigment-permission",
 *     summary="Assigment Permission to other user",
 *     tags={"Users"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="userUuid", type="string"),
 *          @OpenApi\Annotations\Property(property="userPermissionType", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="relatedEntity", type="string"),
 *          @OpenApi\Annotations\Property(property="typeOfMachine", type="number", nullable=true),
 *          @OpenApi\Annotations\Property(property="relatedEntityUuid", type="string"),
 *       )
 *     ),
 * )
 */
class AssignmentPermissionController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new AssignmentPermissionCommand(
            $this->getLoggedUser(),
            $this->request->request->get('userUuid'),
            PermissionType::from($this->request->request->get('userPermissionType')),
            is_null($typeRelatedEntity = $this->request->request->get('relatedEntity')) ?
                null :
                PermissionRelatedEntity::from($typeRelatedEntity)
            ,
            $this->request->request->get('typeOfMachine'),
            $this->request->request->get('relatedEntityUuid'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
