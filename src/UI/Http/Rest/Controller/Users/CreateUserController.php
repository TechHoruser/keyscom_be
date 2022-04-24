<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/user", methods={"POST"})
 *
 * @OpenApi\Annotations\Post (
 *     path="/user",
 *     summary="Create a User",
 *     tags={"Users"},
 *     @OpenApi\Annotations\RequestBody (
 *      @OpenApi\Annotations\JsonContent(
 *        type="object",
 *          @OpenApi\Annotations\Property(property="uuid", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="email", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="firstName", type="string", nullable=true),
 *          @OpenApi\Annotations\Property(property="lastName", type="string", nullable=true),
 *       )
 *     ),
 * )
 */
class CreateUserController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $result = $this->dispatch(new CreateUserCommand(
            $this->getLoggedUser(),
            $this->request->request->get('uuid'),
            $this->request->request->get('email'),
            $this->request->request->get('firstName'),
            $this->request->request->get('lastName'),
        ));

        return new JsonResponse(
            $this->normalizer->normalize($result),
            Response::HTTP_CREATED,
        );
    }
}
