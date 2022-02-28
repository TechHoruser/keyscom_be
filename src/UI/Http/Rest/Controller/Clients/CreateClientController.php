<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\CreateClient\CreateClientCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/client", methods={"POST"})
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
class CreateClientController extends AbstractCommandController
{
    /**
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke()
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
