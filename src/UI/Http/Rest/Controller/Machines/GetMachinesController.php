<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Machines;

use App\Application\UseCase\Machine\GetMachines\GetMachinesQuery;
use App\UI\Http\Rest\Controller\AbstractQueryController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Symfony\Component\Routing\Annotation\Route ("/machine", methods={"GET"})
 *
 * @OpenApi\Annotations\Get (
 *     path="/machine",
 *     summary="Get Machines",
 *     tags={"Machines"},
 *     @OpenApi\Annotations\Parameter (
 *         name="page",
 *         in="query",
 *         description="The number of page to show"
 *     )
 * )
 */
class GetMachinesController extends AbstractQueryController
{
    public function __invoke(): JsonResponse
    {
        $results = $this->dispatch(new GetMachinesQuery(
            $this->generatePaginationPropertiesByQueryParams(),
            $this->request->query->all('filters'),
        ));

        return new JsonResponse($this->normalizer->normalize($results));
    }
}
