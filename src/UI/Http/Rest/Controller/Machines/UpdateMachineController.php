<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Machines;

use App\Application\UseCase\Machine\UpdateMachine\UpdateMachineCommand;
use App\UI\Http\Rest\Controller\AbstractCommandController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/machine/{machineUuid}",
 *     requirements={"machineUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"PUT"},
 * );
 *
 * @OpenApi\Annotations\Put (
 *     path="/machine/{machineUuid}",
 *     summary="Update a Machine",
 *     tags={"Machines"},
 * )
 */
class UpdateMachineController extends AbstractCommandController
{
    public function __invoke(string $machineUuid): JsonResponse
    {
        $this->dispatch(new UpdateMachineCommand(
            $this->getLoggedUser(),
            $machineUuid,
            $this->request->request->get('name'),
        ));

        return new JsonResponse();
    }
}
