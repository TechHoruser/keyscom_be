<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Machines;

use App\Application\UseCase\Machine\DeleteMachine\DeleteMachineCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/machine/{machineUuid}",
 *     requirements={"machineUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"DELETE"},
 * );
 *
 * @OpenApi\Annotations\Delete (
 *     path="/machine/{machineUuid}",
 *     summary="Delete a Machine",
 *     tags={"Machines"},
 * )
 */
class DeleteMachineController extends AbstractCommandController
{
    public function __invoke(string $machineUuid): JsonResponse
    {
        $this->dispatch(new DeleteMachineCommand($machineUuid));

        return new JsonResponse();
    }
}
