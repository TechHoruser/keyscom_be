<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/assigment-permission/rollback", methods={"POST"})
 *
 * @OA\Get (
 *     path="/assigment-permission/rollback",
 *     summary="Rollback Permission to other user",
 *     tags={"Users"},
 *     @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="permissionUuid", type="string"),
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
