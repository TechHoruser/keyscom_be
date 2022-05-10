<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\UpdateClient\UpdateClientCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/client/{clientUuid}",
 *     requirements={"clientUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"PUT"},
 * );
 *
 * @OpenApi\Annotations\Put (
 *     path="/client/{clientUuid}",
 *     summary="Update a Client",
 *     tags={"Clients"},
 * )
 */
class UpdateClientController extends AbstractCommandController
{
    public function __invoke(string $clientUuid): JsonResponse
    {
        $this->dispatch(new UpdateClientCommand(
            $this->getLoggedUser(),
            $clientUuid,
            $this->request->request->get('name'),
        ));

        return new JsonResponse();
    }
}
