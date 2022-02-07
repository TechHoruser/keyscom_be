<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/assigment-permission", methods={"POST"})
 *
 * @OpenApi\Annotations\Get (
 *     path="/assigment-permission",
 *     summary="Assigment Permission to other user",
 *     tags={"Users"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="userUuid", type="string"),
 *          @OpenApi\Annotations\Property(property="userType", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="relatedEntity", type="string"),
 *          @OpenApi\Annotations\Property(property="typeOfMachine", type="number", nullable=true),
 *          @OpenApi\Annotations\Property(property="relatedEntityUuid", type="string"),
 *       )
 *     ),
 * )
 */
class AssignmentPermissionController extends AbstractCommandController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new RollbackPermissionCommand(
            '5c1f1b27-ff41-43a9-95b3-6db79de86903',
            '5c1f1b27-ff41-43a9-95b3-6db79de86903'
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
