<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Machines;

use App\Application\UseCase\Machine\GetMachine\GetMachineQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route (
 *     "/machine/{machineUuid}",
 *     requirements={"machineUuid"="[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}"},
 *     methods={"GET"}
 * );
 *
 * @OpenApi\Annotations\Get (
 *     path="/machine/{machineUuid}",
 *     summary="Get a Machine",
 *     tags={"Machines"},
 *     @OpenApi\Annotations\Parameter (
 *         name="embeds",
 *         in="query",
 *         description="array of embeds"
 *     ),
 * )
 */
class GetMachineController extends AbstractQueryController
{
    public function __invoke(string $machineUuid): JsonResponse
    {
        $results = $this->dispatch(new GetMachineQuery(
            $this->getLoggedUser(),
            $machineUuid,
            $this->request->query->all('embeds'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
