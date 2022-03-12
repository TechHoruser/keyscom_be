<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Clients;

use App\Application\UseCase\Client\DeleteClient\DeleteClientCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/client/{clientUuid}",
 *     requirements={"clientUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"DELETE"},
 * );
 *
 * @OpenApi\Annotations\Delete (
 *     path="/client/{clientUuid}",
 *     summary="Delete a Client",
 *     tags={"Clients"},
 * )
 */
class DeleteClientController extends AbstractCommandController
{
    public function __invoke(string $clientUuid): JsonResponse
    {
        $this->dispatch(new DeleteClientCommand($clientUuid));

        return new JsonResponse();
    }
}
