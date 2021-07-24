<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\AssignmentPermission\AssignmentPermissionCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Route("/assigment-permissions", methods={"POST"})
 *
 * @OA\Get (
 *     path="/assigment-permissions",
 *     summary="Assigment Permission to other user",
 *     tags={"users"},
 *     @OA\RequestBody (
 *      @OA\JsonContent(
 *        type="object",
 *          @OA\Property(property="userUuid", type="string"),
 *          @OA\Property(property="userType", type="string", nullable=true),
 *          @OA\Property(property="relatedEntity", type="string"),
 *          @OA\Property(property="typeOfMachine", type="number"),
 *          @OA\Property(property="relatedEntityUuid", type="string"),
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
        $results = $this->dispatch(new AssignmentPermissionCommand(
            '5c1f1b27-ff41-43a9-95b3-6db79de86903',
            $this->request->request->get('userUuid'),
            $this->request->request->get('userPermissionType'),
            $this->request->request->get('relatedEntity'),
            $this->request->request->get('typeOfMachine'),
            $this->request->request->get('relatedEntityUuid')
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
