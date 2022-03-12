<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\CreateClient\CreateClientCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/client", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/client",
 *     summary="Create a Client",
 *     tags={"Clients"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="userUuid", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="name", type="string", nullable=true),
 *       )
 *     ),
 * )
 */
class CreateClientController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $result = $this->dispatch(new CreateClientCommand(
            $this->request->request->get('uuid'),
            $this->request->request->get('name'),
        ));

        return new JsonResponse(
            $this->normalizer->normalize($result),
            Response::HTTP_CREATED,
        );
    }
}
