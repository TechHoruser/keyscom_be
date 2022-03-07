<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/assigment-permission/rollback", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/assigment-permission/rollback",
 *     summary="Rollback Permission to other user",
 *     tags={"Users"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="permissionUuid", type="string"),
 *       )
 *     ),
 * )
 */
class RollbackAssignmentPermissionController extends AbstractCommandController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new RollbackPermissionCommand(
            $this->request->request->get('permissionUuid')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
