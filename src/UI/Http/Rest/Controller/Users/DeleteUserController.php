<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\DeleteUser\DeleteUserCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/user/{userUuid}",
 *     requirements={"userUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"DELETE"},
 * );
 *
 * @OpenApi\Annotations\Delete (
 *     path="/user/{userUuid}",
 *     summary="Delete a User",
 *     tags={"Users"},
 * )
 */
class DeleteUserController extends AbstractCommandController
{
    public function __invoke(string $userUuid): JsonResponse
    {
        $this->dispatch(new DeleteUserCommand(
            $this->getLoggedUser(),
            $userUuid,
        ));

        return new JsonResponse();
    }
}
