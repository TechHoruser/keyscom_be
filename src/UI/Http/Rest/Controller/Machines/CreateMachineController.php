<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Machines;

use App\Application\UseCase\Machine\CreateMachine\CreateMachineCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/machine", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/machine",
 *     summary="Create a Machine",
 *     tags={"Machines"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="userUuid", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="name", type="string", nullable=true),
 *       )
 *     ),
 * )
 */
class CreateMachineController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $result = $this->dispatch(new CreateMachineCommand(
            $this->getLoggedUser(),
            $this->request->request->get('uuid'),
            $this->request->request->get('ip'),
            $this->request->request->get('name'),
            $this->request->request->get('domain'),
            $this->request->request->get('type'),
            $this->request->request->get('projectUuid'),
        ));

        return new JsonResponse(
            $this->normalizer->normalize($result),
            Response::HTTP_CREATED,
        );
    }
}
