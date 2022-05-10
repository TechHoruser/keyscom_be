<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Users;

use App\Application\UseCase\User\UpdateMySelf\UpdateMySelfCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/user",
 *     methods={"PUT"},
 * );
 *
 * @OpenApi\Annotations\Put (
 *     path="/user",
 *     summary="Update my self user",
 *     tags={"Users"},
 * )
 */
class UpdateMySelfController extends AbstractCommandController
{
    public function __invoke(): JsonResponse
    {
        $this->dispatch(new UpdateMySelfCommand(
            $this->getLoggedUser(),
            $this->request->request->get('firstName'),
            $this->request->request->get('lastName'),
            $this->request->request->get('pubKey'),
        ));

        return new JsonResponse();
    }
}
