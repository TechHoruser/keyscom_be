<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\UpdateUser\UpdateUserCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/user/{userUuid}",
 *     requirements={"userUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"PUT"},
 * );
 *
 * @OpenApi\Annotations\Put (
 *     path="/user/{userUuid}",
 *     summary="Update a User",
 *     tags={"Users"},
 * )
 */
class UpdateUserController extends AbstractCommandController
{
    public function __invoke(string $userUuid): JsonResponse
    {
        $this->dispatch(new UpdateUserCommand(
            $this->getLoggedUser(),
            $userUuid,
            $this->request->request->get('email'),
            $this->request->request->get('firstName'),
            $this->request->request->get('lastName'),
        ));

        return new JsonResponse();
    }
}
