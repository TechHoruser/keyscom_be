<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\RollbackPermission\RollbackPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/rollback-permissions", methods={"POST"})
 *
 * @OA\Get (
 *     path="/rollback-permissions",
 *     summary="Rollback Permission to other user",
 *     tags={"users"},
 *     @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="userUuid", type="string"),
 *          @OA\Property(property="permissionUuid", type="string"),
 *       )
 *     ),
 * )
 */
class RollbackPermissionController extends AbstractCommandController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
    {
        $results = $this->dispatch(new RollbackPermissionCommand(
            $this->request->request->get('userUuid'),
            $this->request->request->get('permissionUuid')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
